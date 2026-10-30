<?php

namespace App\Services\Import;

use App\Models\AnalysisPeriod;
use App\Models\AtmanProfile;
use App\Models\CoreValue;
use App\Models\DataImport;
use App\Models\DataImportChange;
use App\Models\Kpi;
use App\Models\KpiActual;
use App\Models\KpiTarget;
use App\Models\ObjectiveRelation;
use App\Models\StrategicInitiative;
use App\Models\StrategicObjective;
use App\Models\StrategicTheme;
use App\Models\User;
use App\Support\BscContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Options;
use OpenSpout\Reader\XLSX\Reader;
use Throwable;

/**
 * Mengisi data satu perusahaan/perioda dari workbook Excel (format: BscWorkbook).
 *
 * - Kode sasaran/KPI/tema/inisiatif menjadi kunci: upload ulang memperbarui, bukan menggandakan.
 * - Baris yang salah dilewati dan dilaporkan; baris lain tetap diproses.
 * - Pratinjau (dryRun) menjalankan proses yang sama lalu membatalkan transaksi.
 * - Setiap perubahan dicatat di data_import_changes agar import bisa dibatalkan (rollback).
 */
class BscImporter
{
    protected ImportResult $result;

    protected ?DataImport $import = null;

    protected AnalysisPeriod $period;

    protected ?User $user = null;

    protected array $options = [];

    /** @var array<string,?User> */
    protected array $userCache = [];

    public function __construct(protected BscContext $ctx) {}

    /**
     * @param  array{approve_actuals?:bool,file_name?:string,file_path?:string}  $options
     */
    public function run(string $path, AnalysisPeriod $period, ?User $user, array $options = [], bool $dryRun = false): ImportResult
    {
        $this->result = new ImportResult($dryRun);
        $this->period = $period;
        $this->user = $user;
        $this->options = $options;
        $this->userCache = [];

        try {
            $sheets = $this->read($path);
        } catch (Throwable $e) {
            $this->result->error('File', null, 'File tidak bisa dibaca sebagai Excel (.xlsx): '.$e->getMessage());

            return $this->result;
        }
        if ($sheets === []) {
            $this->result->error('File', null, 'Tidak ada sheet yang dikenali. Nama sheet harus sesuai template: '.implode(', ', array_keys(BscWorkbook::SHEETS)).'.');

            return $this->result;
        }

        $prevCompany = $this->ctx->companyId();
        $prevPeriod = $this->ctx->periodId();
        $this->ctx->set($period->company_id, $period->id);
        DB::beginTransaction();
        try {
            $this->import = DataImport::create([
                'company_id' => $period->company_id,
                'analysis_period_id' => $period->id,
                'user_id' => $user?->id,
                'file_name' => $options['file_name'] ?? basename($path),
                'file_path' => $options['file_path'] ?? null,
                'status' => 'selesai',
                'options' => ['approve_actuals' => $this->approvesActuals()],
            ]);
            foreach (array_keys(BscWorkbook::SHEETS) as $sheet) {
                if (isset($sheets[$sheet])) {
                    $this->processSheet($sheet, $sheets[$sheet]);
                }
            }
            $this->import->update([
                'summary' => $this->result->summary,
                'issues' => $this->result->issues,
                'rows_ok' => $this->result->rowsOk(),
                'rows_failed' => $this->result->rowsFailed(),
            ]);
            if ($dryRun) {
                DB::rollBack();
            } else {
                DB::commit();
                $this->result->import = $this->import;
            }
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        } finally {
            $this->ctx->set($prevCompany, $prevPeriod);
        }

        return $this->result;
    }

    /** Batalkan import: hapus data yang dibuat dan kembalikan nilai lama data yang diubah. */
    public function rollback(DataImport $import, ?User $user): void
    {
        abort_unless($import->canRollback(), 422, 'Hanya import terakhir yang belum dibatalkan yang bisa dibatalkan.');
        $this->ctx->withoutScope(function () use ($import, $user) {
            DB::transaction(function () use ($import, $user) {
                foreach ($import->changes()->orderByDesc('id')->get() as $change) {
                    /** @var Model|null $model */
                    $model = $change->model_type::find($change->model_id);
                    if (! $model) {
                        continue;
                    }
                    if ($change->action === 'created') {
                        $model->delete();

                        continue;
                    }
                    $old = $change->old_values ?? [];
                    if (array_key_exists('__objectives', $old) && $model instanceof StrategicInitiative) {
                        $model->objectives()->sync($old['__objectives']);
                        unset($old['__objectives']);
                    }
                    if ($old !== []) {
                        $model->forceFill($old)->save();
                    }
                }
                $import->update(['status' => 'dibatalkan', 'rolled_back_at' => now(), 'rolled_back_by' => $user?->id]);
            });
        });
    }

    // ------------------------------------------------------------------ membaca file

    /** @return array<string,array<int,array<string,mixed>>> nama sheet resmi => [nomor baris => [kolom => nilai]] */
    protected function read(string $path): array
    {
        $options = new Options;
        $options->SHOULD_FORMAT_DATES = false;
        $reader = new Reader($options);
        $reader->open($path);
        $out = [];
        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                $name = BscWorkbook::canonicalSheet($sheet->getName());
                if (! $name) {
                    continue;
                }
                $headers = null;
                $rows = [];
                foreach ($sheet->getRowIterator() as $i => $row) {
                    $values = $row->toArray();
                    if ($headers === null) {
                        if (array_filter($values, fn ($v) => ! BscWorkbook::isBlank($v)) === []) {
                            continue;
                        }
                        $headers = array_map([BscWorkbook::class, 'headerKey'], $values);

                        continue;
                    }
                    if (array_filter($values, fn ($v) => ! BscWorkbook::isBlank($v)) === []) {
                        continue;
                    }
                    $assoc = [];
                    foreach ($headers as $c => $h) {
                        if ($h !== '') {
                            $assoc[$h] = $values[$c] ?? null;
                        }
                    }
                    $rows[$i] = $assoc;
                }
                $out[$name] = $rows;
            }
        } finally {
            $reader->close();
        }

        return $out;
    }

    // ------------------------------------------------------------------ proses per sheet

    protected function processSheet(string $sheet, array $rows): void
    {
        $method = match ($sheet) {
            'Jati Diri' => 'rowAtman',
            'Nilai Inti' => 'rowCoreValue',
            'Tema' => 'rowTheme',
            'Sasaran' => 'rowObjective',
            'Relasi Sasaran' => 'rowRelation',
            'KPI' => 'rowKpi',
            'Target' => 'rowTargets',
            'Realisasi' => 'rowActuals',
            'Inisiatif' => 'rowInitiative',
        };
        foreach ($rows as $line => $row) {
            $before = $this->result->summary;
            try {
                DB::transaction(fn () => $this->{$method}($sheet, $line, $row));
            } catch (ImportRowException $e) {
                $this->result->summary = $before; // perubahan baris ini ikut dibatalkan
                $this->result->count($sheet, 'gagal');
                $this->result->error($sheet, $line, $e->getMessage());
            }
        }
    }

    protected function rowAtman(string $sheet, int $line, array $row): void
    {
        $part = BscWorkbook::headerKey($this->required($row, 'bagian'));
        $field = BscWorkbook::ATMAN_FIELDS[$part] ?? null;
        if (! $field) {
            throw new ImportRowException('Bagian "'.$row['bagian'].'" tidak dikenali. Pakai: '.implode(', ', array_keys(BscWorkbook::ATMAN_FIELDS)).'.');
        }
        $profile = AtmanProfile::firstOrNew([]);
        $profile->{$field} = BscWorkbook::text($row['isi'] ?? null);
        $this->persist($sheet, $profile);
    }

    protected function rowCoreValue(string $sheet, int $line, array $row): void
    {
        $name = $this->required($row, 'nama');
        $value = CoreValue::firstOrNew(['name' => $name]);
        $value->fill([
            'local_name' => BscWorkbook::text($row['nama_lokal'] ?? null),
            'description' => BscWorkbook::text($row['deskripsi'] ?? null),
            'behaviors' => BscWorkbook::text($row['perilaku'] ?? null),
        ]);
        if (! $value->exists) {
            $value->sort = CoreValue::count() + 1;
        }
        $this->persist($sheet, $value);
    }

    protected function rowTheme(string $sheet, int $line, array $row): void
    {
        $theme = StrategicTheme::firstOrNew(['code' => $this->required($row, 'kode')]);
        $theme->fill([
            'name' => $this->required($row, 'nama'),
            'description' => BscWorkbook::text($row['deskripsi'] ?? null),
            'color' => BscWorkbook::text($row['warna'] ?? null),
        ]);
        if (! $theme->exists) {
            $theme->sort = StrategicTheme::count() + 1;
        }
        $this->persist($sheet, $theme);
    }

    protected function rowObjective(string $sheet, int $line, array $row): void
    {
        $objective = StrategicObjective::firstOrNew(['code' => $this->required($row, 'kode')]);
        $themeCode = BscWorkbook::text($row['kode_tema'] ?? null);
        $theme = $themeCode ? StrategicTheme::where('code', $themeCode)->first() : null;
        if ($themeCode && ! $theme) {
            throw new ImportRowException('Kode tema "'.$themeCode.'" belum ada. Isi sheet Tema terlebih dulu.');
        }
        $objective->fill([
            'perspective' => BscWorkbook::perspective($this->required($row, 'perspektif')),
            'title' => $this->required($row, 'nama_sasaran'),
            'description' => BscWorkbook::text($row['deskripsi'] ?? null),
            'strategic_theme_id' => $theme?->id,
            'weight' => $this->weight($row),
            'owner_id' => $this->owner($sheet, $line, $row['email_pemilik'] ?? null),
        ]);
        if (! $objective->exists) {
            $objective->sort = StrategicObjective::where('perspective', $objective->perspective)->count() + 1;
        }
        $this->persist($sheet, $objective);
    }

    protected function rowRelation(string $sheet, int $line, array $row): void
    {
        $source = $this->objective($this->required($row, 'kode_penyebab'));
        $target = $this->objective($this->required($row, 'kode_akibat'));
        if ($source->is($target)) {
            throw new ImportRowException('Sasaran penyebab dan akibat tidak boleh sama.');
        }
        $strength = BscWorkbook::number($row['kekuatan'] ?? null) ?? 2;
        if ($strength < 1 || $strength > 3) {
            throw new ImportRowException('Kekuatan relasi harus 1 sampai 3.');
        }
        $relation = ObjectiveRelation::firstOrNew(['source_objective_id' => $source->id, 'target_objective_id' => $target->id]);
        $relation->fill(['strength' => (int) $strength, 'notes' => BscWorkbook::text($row['catatan'] ?? null)]);
        $this->persist($sheet, $relation);
    }

    protected function rowKpi(string $sheet, int $line, array $row): void
    {
        $kpi = Kpi::firstOrNew(['code' => $this->required($row, 'kode')]);
        $decimals = BscWorkbook::number($row['desimal'] ?? null);
        $kpi->fill([
            'strategic_objective_id' => $this->objective($this->required($row, 'kode_sasaran'))->id,
            'name' => $this->required($row, 'nama_kpi'),
            'unit' => $this->required($row, 'satuan'),
            'direction' => BscWorkbook::direction($row['polaritas'] ?? null),
            'frequency' => BscWorkbook::frequency($row['frekuensi'] ?? null),
            'aggregation' => BscWorkbook::aggregation($row['agregasi'] ?? null),
            'weight' => $this->weight($row),
            'baseline' => BscWorkbook::number($row['baseline'] ?? null),
            'annual_target' => BscWorkbook::number($row['target_tahunan'] ?? null),
            'decimals' => $decimals === null ? 0 : max(0, min(4, (int) $decimals)),
            'is_financial' => BscWorkbook::bool($row['kpi_keuangan'] ?? null),
            'is_critical' => BscWorkbook::bool($row['kpi_kritis'] ?? null),
            'data_source' => BscWorkbook::text($row['sumber_data'] ?? null),
            'description' => BscWorkbook::text($row['deskripsi'] ?? null),
            'owner_id' => $this->owner($sheet, $line, $row['email_pemilik'] ?? null),
        ]);
        if (! $kpi->exists) {
            $kpi->is_active = true;
        }
        $this->persist($sheet, $kpi);
    }

    protected function rowTargets(string $sheet, int $line, array $row): void
    {
        $kpi = $this->kpi($this->required($row, 'kode_kpi'));
        foreach ($this->monthValues($row, 'target') as $month => $value) {
            $target = KpiTarget::firstOrNew(['kpi_id' => $kpi->id, 'month' => $month]);
            $target->fill(['target' => $value, 'notes' => BscWorkbook::text($row['catatan'] ?? null) ?? $target->notes]);
            $this->persist($sheet, $target);
        }
    }

    protected function rowActuals(string $sheet, int $line, array $row): void
    {
        $kpi = $this->kpi($this->required($row, 'kode_kpi'));
        $approve = $this->approvesActuals();
        $values = $this->monthValues($row, 'realisasi');
        foreach ($values as $month => $value) {
            $actual = KpiActual::firstOrNew(['kpi_id' => $kpi->id, 'month' => $month]);
            if ($actual->exists && $actual->status === 'disetujui' && ! $this->user?->hasPermission('kpi.approve_actual', $this->period->company_id)) {
                throw new ImportRowException('Realisasi '.$kpi->code.' bulan '.$month.' sudah disetujui dan terkunci; hanya Pimpinan yang bisa mengoreksi.');
            }
            $actual->fill([
                'actual' => $value,
                'notes' => BscWorkbook::text($row['catatan'] ?? null) ?? $actual->notes,
                'evidence' => BscWorkbook::text($row['bukti'] ?? null) ?? $actual->evidence ?? 'Import '.($this->options['file_name'] ?? 'Excel'),
                'is_projection' => false,
                'submitted_by' => $this->user?->id,
                'submitted_at' => now(),
            ]);
            if ($approve) {
                $actual->fill(['status' => 'disetujui', 'approved_by' => $this->user?->id, 'approved_at' => now()]);
            } elseif (! $actual->exists || $actual->isDirty('actual')) {
                $actual->fill(['status' => 'draf', 'verified_by' => null, 'verified_at' => null, 'approved_by' => null, 'approved_at' => null]);
            }
            $this->persist($sheet, $actual);
        }
    }

    protected function rowInitiative(string $sheet, int $line, array $row): void
    {
        $initiative = StrategicInitiative::firstOrNew(['code' => $this->required($row, 'kode')]);
        $themeCode = BscWorkbook::text($row['kode_tema'] ?? null);
        $theme = $themeCode ? StrategicTheme::where('code', $themeCode)->first() : null;
        if ($themeCode && ! $theme) {
            throw new ImportRowException('Kode tema "'.$themeCode.'" belum ada.');
        }
        $objectiveIds = array_map(fn ($c) => $this->objective($c)->id, BscWorkbook::codes($row['kode_sasaran'] ?? null));
        $start = BscWorkbook::date($row['mulai'] ?? null);
        $end = BscWorkbook::date($row['selesai'] ?? null);
        if ($start && $end && $end < $start) {
            throw new ImportRowException('Tanggal selesai lebih awal dari tanggal mulai.');
        }
        $initiative->fill([
            'title' => $this->required($row, 'nama_inisiatif'),
            'description' => BscWorkbook::text($row['deskripsi'] ?? null),
            'expected_outcome' => BscWorkbook::text($row['hasil_diharapkan'] ?? null),
            'strategic_theme_id' => $theme?->id,
            'owner_id' => $this->owner($sheet, $line, $row['email_pic'] ?? null),
            'start_date' => $start,
            'end_date' => $end,
            'status' => BscWorkbook::initiativeStatus($row['status'] ?? null),
            'impact' => BscWorkbook::scale($row['dampak'] ?? null),
            'urgency' => BscWorkbook::scale($row['urgensi'] ?? null),
            'risk' => BscWorkbook::scale($row['risiko'] ?? null),
            'budget_level' => BscWorkbook::scale($row['tingkat_anggaran'] ?? null),
            'budget_planned' => BscWorkbook::number($row['anggaran'] ?? null) ?? 0,
        ]);
        $oldObjectives = $initiative->exists ? $initiative->objectives()->pluck('strategic_objectives.id')->map(fn ($i) => (int) $i)->sort()->values()->all() : [];
        $newObjectives = collect($objectiveIds)->unique()->sort()->values()->all();
        $objectivesChanged = $initiative->exists && $oldObjectives !== $newObjectives;
        $this->persist($sheet, $initiative, $objectivesChanged ? ['__objectives' => $oldObjectives] : [], $objectivesChanged);
        $initiative->objectives()->sync($newObjectives);
    }

    // ------------------------------------------------------------------ pembantu

    /**
     * Simpan model dan catat jejaknya untuk rollback.
     *
     * @param  array<string,mixed>  $extraOld  nilai lama tambahan (mis. relasi many-to-many)
     */
    protected function persist(string $sheet, Model $model, array $extraOld = [], bool $forceChanged = false): void
    {
        if ($model->exists && ! $model->isDirty() && ! $forceChanged) {
            $this->result->count($sheet, 'sama');

            return;
        }
        $created = ! $model->exists;
        $old = $created ? null : array_intersect_key($model->getRawOriginal(), $model->getDirty()) + $extraOld;
        $model->save();
        DataImportChange::create([
            'data_import_id' => $this->import->id,
            'model_type' => $model::class,
            'model_id' => $model->getKey(),
            'action' => $created ? 'created' : 'updated',
            'old_values' => $old,
        ]);
        $this->result->count($sheet, $created ? 'dibuat' : 'diubah');
    }

    protected function required(array $row, string $key): string
    {
        $v = BscWorkbook::text($row[$key] ?? null);
        if ($v === null) {
            throw new ImportRowException('Kolom '.$key.' wajib diisi.');
        }

        return $v;
    }

    protected function weight(array $row): float
    {
        $w = BscWorkbook::number($row['bobot'] ?? null) ?? 1;
        if ($w <= 0) {
            throw new ImportRowException('Bobot harus lebih dari 0.');
        }

        return $w;
    }

    protected function objective(string $code): StrategicObjective
    {
        return StrategicObjective::where('code', $code)->first()
            ?? throw new ImportRowException('Kode sasaran "'.$code.'" belum ada di perioda ini. Isi sheet Sasaran terlebih dulu.');
    }

    protected function kpi(string $code): Kpi
    {
        return Kpi::where('code', $code)->first()
            ?? throw new ImportRowException('Kode KPI "'.$code.'" belum ada di perioda ini. Isi sheet KPI terlebih dulu.');
    }

    /** Email pemilik/PIC -> id pengguna yang punya akses ke perusahaan ini; selain itu peringatan dan dikosongkan. */
    protected function owner(string $sheet, int $line, mixed $email): ?int
    {
        $email = BscWorkbook::text($email);
        if ($email === null) {
            return null;
        }
        $key = mb_strtolower($email);
        if (! array_key_exists($key, $this->userCache)) {
            $user = User::where('email', $key)->first();
            $this->userCache[$key] = $user && in_array((int) $this->period->company_id, $user->accessibleCompanyIds(), true) ? $user : null;
        }
        if (! $this->userCache[$key]) {
            $this->result->warning($sheet, $line, 'Pengguna '.$email.' tidak terdaftar di perusahaan ini; penanggung jawab dikosongkan.');
        }

        return $this->userCache[$key]?->id;
    }

    /**
     * Nilai per bulan dari format lebar (kolom jan..des) atau format panjang (kolom bulan + nilai).
     *
     * @return array<int,float>
     */
    protected function monthValues(array $row, string $valueColumn): array
    {
        $out = [];
        if (array_key_exists('bulan', $row)) {
            $value = BscWorkbook::number($row[$valueColumn] ?? $row['nilai'] ?? null);
            if ($value === null) {
                throw new ImportRowException('Kolom '.$valueColumn.' wajib diisi.');
            }
            $out[BscWorkbook::month($row['bulan'])] = $value;

            return $out;
        }
        foreach (BscWorkbook::MONTH_COLUMNS as $i => $col) {
            $value = BscWorkbook::number($row[$col] ?? null);
            if ($value !== null) {
                $out[$i + 1] = $value;
            }
        }
        if ($out === []) {
            throw new ImportRowException('Tidak ada nilai bulan yang diisi (kolom jan sampai des).');
        }

        return $out;
    }

    protected function approvesActuals(): bool
    {
        return ! empty($this->options['approve_actuals']) && (bool) $this->user?->hasPermission('kpi.approve_actual', $this->period->company_id);
    }
}
