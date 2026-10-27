<?php

namespace App\Services\Import;

use App\Models\AnalysisPeriod;
use App\Models\AtmanProfile;
use App\Models\CoreValue;
use App\Models\Kpi;
use App\Models\ObjectiveRelation;
use App\Models\StrategicInitiative;
use App\Models\StrategicObjective;
use App\Models\StrategicTheme;
use App\Support\BscContext;
use App\Support\Terms;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

/** Menulis workbook dengan format BscWorkbook: template kosong, atau data perusahaan/perioda yang ada (untuk diubah lalu diupload ulang). */
class BscTemplate
{
    public function __construct(protected BscContext $ctx) {}

    /** Template kosong: sheet Petunjuk + header setiap sheet. */
    public function blank(string $file): string
    {
        return $this->write($file, array_fill_keys(array_keys(BscWorkbook::SHEETS), []), null);
    }

    /**
     * Workbook dari baris yang sudah disiapkan (dipakai seeder perusahaan contoh).
     *
     * @param  array<string,array<int,array<int,mixed>>>  $data  nama sheet => baris (urutan kolom sesuai BscWorkbook::SHEETS)
     */
    public function withRows(string $file, array $data): string
    {
        return $this->write($file, $data, null);
    }

    /** Data perioda dalam format template. */
    public function export(string $file, AnalysisPeriod $period): string
    {
        $prevCompany = $this->ctx->companyId();
        $prevPeriod = $this->ctx->periodId();
        $this->ctx->set($period->company_id, $period->id);
        try {
            return $this->write($file, $this->rows(), $period);
        } finally {
            $this->ctx->set($prevCompany, $prevPeriod);
        }
    }

    /** @param array<string,array<int,array<int,mixed>>> $data */
    protected function write(string $file, array $data, ?AnalysisPeriod $period): string
    {
        @mkdir(dirname($file), 0775, true);
        $head = (new Style)->setFontBold()->setFontColor(Color::WHITE)->setBackgroundColor('34506E');
        $bold = (new Style)->setFontBold();
        $w = new Writer;
        $w->openToFile($file);

        $sheet = $w->getCurrentSheet();
        $sheet->setName('Petunjuk');
        $w->addRow(Row::fromValues([config('bsc.brand_name').' — template data perusahaan'], $bold));
        if ($period) {
            $w->addRow(Row::fromValues(['Perusahaan', $period->company?->name]));
            $w->addRow(Row::fromValues(['Perioda', $period->name]));
            if ($notice = \App\Support\DemoNotice::for($period->company)) {
                $w->addRow(Row::fromValues(['PERINGATAN', $notice], $bold));
            }
        }
        $w->addRow(Row::fromValues([]));
        foreach ([
            'Satu file berisi data satu perusahaan untuk satu perioda. Pilih perusahaan & perioda di aplikasi sebelum mengupload.',
            'Sheet boleh diisi bertahap; sheet yang tidak ada di file tidak diubah. Urutan proses: Jati Diri, Nilai Inti, Tema, Sasaran, Relasi Sasaran, KPI, Target, Realisasi, Inisiatif.',
            'Kolom bertanda * wajib diisi. Kode (sasaran, KPI, tema, inisiatif) menjadi kunci: upload ulang memperbarui data dengan kode yang sama.',
            'Angka boleh format Excel biasa atau teks Indonesia (11.098.665 atau 6,62).',
            'Baris yang salah dilewati dan dilaporkan per baris; baris lain tetap diproses. Gunakan Pratinjau dulu sebelum Proses Import.',
        ] as $line) {
            $w->addRow(Row::fromValues(['• '.$line]));
        }
        $w->addRow(Row::fromValues([]));
        $w->addRow(Row::fromValues(['Sheet', 'Kolom', 'Keterangan'], $head));
        foreach (BscWorkbook::SHEETS as $name => $spec) {
            $w->addRow(Row::fromValues([$name, implode(', ', $spec['columns']), $spec['help']]));
        }
        $w->addRow(Row::fromValues([]));
        $w->addRow(Row::fromValues(['Perspektif yang dikenali', implode(', ', Terms::perspectives())]));

        foreach (BscWorkbook::SHEETS as $name => $spec) {
            $w->addNewSheetAndMakeItCurrent()->setName($name);
            $w->addRow(Row::fromValues($spec['columns'], $head));
            foreach ($data[$name] ?? [] as $values) {
                $w->addRow(Row::fromValues($values));
            }
        }
        $w->close();

        return $file;
    }

    /** @return array<string,array<int,array<int,mixed>>> */
    protected function rows(): array
    {
        $num = fn ($v) => $v === null ? null : (float) $v;
        $out = array_fill_keys(array_keys(BscWorkbook::SHEETS), []);

        if ($profile = AtmanProfile::first()) {
            foreach (BscWorkbook::ATMAN_FIELDS as $part => $field) {
                if (filled($profile->{$field})) {
                    $out['Jati Diri'][] = [$part, $profile->{$field}];
                }
            }
        }
        foreach (CoreValue::orderBy('sort')->get() as $v) {
            $out['Nilai Inti'][] = [$v->name, $v->local_name, $v->description, $v->behaviors];
        }
        foreach (StrategicTheme::orderBy('sort')->get() as $t) {
            $out['Tema'][] = [$t->code, $t->name, $t->description, $t->color];
        }
        $objectives = StrategicObjective::with(['theme', 'owner'])->orderBy('perspective')->orderBy('sort')->get();
        foreach ($objectives as $o) {
            $out['Sasaran'][] = [$o->code, Terms::perspective($o->perspective), $o->title, $o->theme?->code, $o->description, (float) $o->weight, $o->owner?->email];
        }
        foreach (ObjectiveRelation::with(['source', 'target'])->get() as $r) {
            $out['Relasi Sasaran'][] = [$r->source?->code, $r->target?->code, $r->strength, $r->notes];
        }
        $kpis = Kpi::with(['objective', 'owner', 'targets', 'actuals'])->orderBy('code')->get();
        foreach ($kpis as $k) {
            $out['KPI'][] = [$k->code, $k->objective?->code, $k->name, $k->unit, $k->direction === 'lower' ? 'turun' : 'naik', $k->frequency,
                ['sum' => 'jumlah', 'average' => 'rata-rata', 'last' => 'terakhir'][$k->aggregation] ?? $k->aggregation,
                (float) $k->weight, $num($k->baseline), $num($k->annual_target), $k->decimals, $k->is_financial ? 'ya' : 'tidak',
                $k->is_critical ? 'ya' : 'tidak', $k->data_source, $k->owner?->email, $k->description];
            if ($k->targets->isNotEmpty()) {
                $t = $k->targets->keyBy('month');
                $out['Target'][] = array_merge([$k->code], array_map(fn ($m) => $num($t->get($m)?->target), range(1, 12)), [null]);
            }
            $actuals = $k->actuals->where('status', '!=', 'dikembalikan')->keyBy('month');
            if ($actuals->isNotEmpty()) {
                $out['Realisasi'][] = array_merge([$k->code], array_map(fn ($m) => $num($actuals->get($m)?->actual), range(1, 12)), [null, null]);
            }
        }
        foreach (StrategicInitiative::with(['objectives', 'theme', 'owner'])->orderBy('code')->get() as $i) {
            $out['Inisiatif'][] = [$i->code, $i->title, $i->objectives->pluck('code')->join(', '), $i->theme?->code, $i->owner?->email,
                $i->start_date?->format('Y-m-d'), $i->end_date?->format('Y-m-d'), $i->status, $i->impact, $i->urgency, $i->risk,
                $i->budget_level, (float) $i->budget_planned, $i->description, $i->expected_outcome];
        }

        return $out;
    }
}
