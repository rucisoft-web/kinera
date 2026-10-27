<?php

namespace Database\Seeders;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\CorrectiveAction;
use App\Models\Finding;
use App\Models\InitiativeActivity;
use App\Models\InitiativeBudget;
use App\Models\InitiativeMilestone;
use App\Models\Institutionalization;
use App\Models\Kpi;
use App\Models\KpiActual;
use App\Models\KpiTarget;
use App\Models\MaturityAnswer;
use App\Models\MaturityAssessment;
use App\Models\MaturityQuestion;
use App\Models\PerformanceReview;
use App\Models\Role;
use App\Models\StrategicDependency;
use App\Models\StrategicInitiative;
use App\Models\StrategicIssue;
use App\Models\StrategicObjective;
use App\Models\StrategicTheme;
use App\Models\User;
use App\Models\UserContext;
use App\Services\MaturityScoringService;
use App\Support\BscContext;
use Illuminate\Support\Facades\Hash;

/**
 * Kerangka demo lengkap satu perusahaan: perioda 2025 (ditutup, angka tahunan dari laporan tahunan)
 * dan 2026 (berjalan, simulasi Jan–Sep). Subkelas hanya menyediakan isi; penyimpanan, deret bulanan,
 * dan alur persetujuan realisasi diatur di sini supaya setiap perusahaan demo berperilaku sama.
 *
 * Kunci pengguna wajib: dirut (penyetuju), cfo (verifikator), strategi (notulis), komisaris.
 */
abstract class ShowcaseSeeder
{
    public const CODE = '';

    /** Nama sumber angka tahunan 2025. */
    public const LT = '';

    /** @var array<string,User> */
    protected array $u = [];

    protected Company $company;

    public function __construct(protected string $password) {}

    // ------------------------------------------------------------------ isi yang wajib disediakan subkelas

    abstract protected function companyAttributes(): array;

    /** @return array<string,array{0:string,1:string,2:string,3:string,4:string[]}> kunci => [nama, email lokal, jabatan, unit, peran] */
    abstract protected function userDefs(): array;

    abstract protected function periodNote(int $year): string;

    /** @return array<int,array<int,float>> bobot musiman per tahun (jumlah 12 bulan = 1) */
    abstract protected function season(): array;

    abstract protected function identity(int $year): void;

    /** @return array<string,StrategicIssue> */
    abstract protected function ecosystem(int $year): array;

    abstract protected function tows(array $issues): void;

    /** @return array{0:array<string,StrategicTheme>,1:array<string,StrategicObjective>} */
    abstract protected function strategy(int $year): array;

    /**
     * kode, sasaran, nama, satuan, arah, agregasi, desimal, keuangan, kritis, pemilik, rumus, sumber,
     * baseline 2024, target 2025, realisasi 2025, target 2026, faktor realisasi 2026, dari laporan tahunan?, frekuensi
     */
    abstract protected function kpiDefs(): array;

    /** @return array<string,PerformanceReview> */
    abstract protected function reviews(int $year): array;

    /** @return array{defs:array,activities:array,milestones:array,budgets:array} */
    abstract protected function initiativeData(int $year): array;

    abstract protected function findingData(int $year): array;

    /** @return array{dependencies:array,institutionalizations:array} */
    abstract protected function continuityData(int $year): array;

    /** KPI 2026 yang sengaja di atas target supaya campuran hijau/kuning/merah realistis. */
    protected function onTrack2026(): array
    {
        return [];
    }

    /** KPI yang realisasi September 2026-nya belum diinput (memicu pengingat). */
    protected function missingSeptember(): array
    {
        return [];
    }

    /** @return array{0:string,1:string}|null [kode KPI, catatan] realisasi September yang dikembalikan */
    protected function returnedSeptember(): ?array
    {
        return null;
    }

    /** KPI yang ditandai kritis mulai 2026. */
    protected function criticalIn2026(): array
    {
        return [];
    }

    /** Skor dasar kematangan per pertanyaan (berulang), 2026 = +1. */
    protected function maturityPattern(): array
    {
        return [3, 3, 2, 3, 2];
    }

    /** @param array<int,float> $raw bobot relatif per bulan → dinormalkan supaya jumlahnya 1 */
    protected static function weights(array $raw): array
    {
        $sum = array_sum($raw);

        return array_map(fn ($v) => $v / $sum, $raw);
    }

    // ------------------------------------------------------------------ alur utama

    public function run(): Company
    {
        $this->company = Company::updateOrCreate(['code' => static::CODE], $this->companyAttributes() + ['is_active' => true, 'is_demo' => true, 'demo_source' => static::LT]);
        $this->users();

        $p2025 = AnalysisPeriod::updateOrCreate(['company_id' => $this->company->id, 'code' => '2025'], [
            'name' => 'Tahun 2025', 'year' => 2025, 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'status' => 'ditutup', 'is_active' => false,
            'notes' => $this->periodNote(2025),
        ]);
        $p2026 = AnalysisPeriod::updateOrCreate(['company_id' => $this->company->id, 'code' => '2026'], [
            'name' => 'Tahun 2026', 'year' => 2026, 'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'status' => 'berjalan', 'is_active' => true,
            'notes' => $this->periodNote(2026),
        ]);
        AnalysisPeriod::where('company_id', $this->company->id)->whereNotIn('id', [$p2025->id, $p2026->id])->update(['is_active' => false]);

        $ctx = app(BscContext::class);
        foreach ([$p2025, $p2026] as $period) {
            $ctx->set($this->company->id, $period->id);
            $this->period($period->year);
        }
        foreach ($this->u as $user) {
            UserContext::updateOrCreate(['user_id' => $user->id], ['company_id' => $this->company->id, 'analysis_period_id' => $p2026->id]);
        }

        return $this->company;
    }

    protected function users(): void
    {
        foreach ($this->userDefs() as $key => [$name, $email, $title, $dept, $roles]) {
            $user = User::updateOrCreate(['email' => $email.'@contoh.local'], [
                'name' => $name, 'job_title' => $title, 'department' => $dept, 'is_active' => true,
                'password' => Hash::make($this->password), 'must_change_password' => false, 'email_verified_at' => now(),
            ]);
            $user->companies()->syncWithoutDetaching([$this->company->id]);
            foreach ($roles as $slug) {
                $role = Role::where('slug', $slug)->firstOrFail();
                if (! $user->roles()->where('roles.id', $role->id)->wherePivot('company_id', $this->company->id)->exists()) {
                    $user->roles()->attach($role->id, ['company_id' => $this->company->id]);
                }
            }
            $this->u[$key] = $user;
        }
    }

    protected function id(string $key): int
    {
        return $this->u[$key]->id;
    }

    protected function period(int $year): void
    {
        $this->identity($year);
        $issues = $this->ecosystem($year);
        [$themes, $obj] = $this->strategy($year);
        $kpis = $this->kpis($year, $obj);
        $reviews = $this->reviews($year);
        $ini = $this->initiatives($year, $themes, $obj);
        $this->findings($year, $kpis, $ini, $reviews);
        $this->continuity($year, $ini, $obj);
        $this->tows($issues);
    }

    // ------------------------------------------------------------------ KPI

    /** @return array<string,Kpi> */
    protected function kpis(int $year, array $obj): array
    {
        $out = [];
        foreach ($this->kpiDefs() as $n => $d) {
            [$code, $o, $name, $unit, $dir, $agg, $dec, $fin, $crit, $owner, $formula, $source, $b24, $t25, $a25, $t26, $f26, $fromLt] = $d;
            $freq = $d[18] ?? 'bulanan';
            $months = match ($freq) {
                'kuartalan' => [3, 6, 9, 12],
                'semesteran' => [6, 12],
                'tahunan' => [12],
                default => range(1, 12),
            };
            $baseline = $year === 2025 ? $b24 : $a25;
            $annualTarget = $year === 2025 ? $t25 : $t26;
            $targets = $this->targetSeries($agg, $baseline, $annualTarget, $dec, $year, $months);
            if ($year === 2025) {
                $actuals = $this->actualSeries2025($agg, $b24, $a25, $dec, $months, 10 + $n);
            } else {
                mt_srand(500 + $n);
                if (in_array($code, $this->onTrack2026(), true)) {
                    $f26 = $dir === 'lower' ? min($f26, .97) : max($f26, 1.025);
                }
                $actuals = [];
                foreach ($targets as $m => $t) {
                    if ($m <= 9) {
                        $v = $t * $f26 * (1 + mt_rand(-150, 150) / 10000);
                        $actuals[$m] = round($unit === '%' && $t <= 100 ? min($v, 100) : $v, $dec);
                    }
                }
                if (in_array($code, $this->missingSeptember(), true)) {
                    unset($actuals[9]); // belum diinput — memicu pengingat
                }
            }

            $kpi = Kpi::create([
                'code' => $code, 'strategic_objective_id' => $obj[$o]->id, 'name' => $name, 'unit' => $unit, 'direction' => $dir, 'aggregation' => $agg,
                'frequency' => $freq, 'owner_id' => $this->id($owner), 'weight' => 1, 'is_financial' => $fin,
                'is_critical' => $crit || ($year === 2026 && in_array($code, $this->criticalIn2026(), true)),
                'decimals' => $dec, 'baseline' => $baseline, 'annual_target' => $annualTarget, 'formula' => $formula, 'data_source' => $source,
                'description' => $fromLt ? 'DATA DEMO. Realisasi tahunan 2025 secara garis besar mengacu pada '.static::LT.'.' : 'DATA DEMO. Indikator ilustrasi untuk penyajian fitur (angka simulasi).',
                'is_active' => true,
            ] + $this->kpiExtra($code, $year));
            foreach ($targets as $m => $t) {
                KpiTarget::create(['kpi_id' => $kpi->id, 'month' => $m, 'target' => $t]);
            }
            foreach ($actuals as $m => $v) {
                $this->actual($kpi, $year, $m, $v, $fromLt, $fin, $owner);
            }
            $out[$code] = $kpi;
        }
        $this->afterKpis($year, $out);

        return $out;
    }

    /** Atribut tambahan Indikator Kinerja (mis. aspek & Definisi Operasional BLU). */
    protected function kpiExtra(string $code, int $year): array
    {
        return [];
    }

    /** @param array<string,Kpi> $kpis dipanggil setelah seluruh indikator & realisasi satu perioda tersimpan. */
    protected function afterKpis(int $year, array $kpis): void {}

    /** Bobot musiman sebuah jendela pelaporan: jumlah bobot bulan sejak periode lapor sebelumnya s.d. bulan $m. */
    protected function windowWeight(array $w, array $months, int $m): float
    {
        $i = array_search($m, $months, true);
        $from = $i === 0 ? 1 : $months[$i - 1] + 1;

        return array_sum(array_map(fn ($x) => $w[$x], range($from, $m)));
    }

    /** @param int[] $months */
    protected function targetSeries(string $agg, ?float $base, float $annual, int $dec, int $year, array $months): array
    {
        $out = [];
        $w = $this->season()[$year];
        $base ??= $annual;
        foreach ($months as $m) {
            $out[$m] = round(match ($agg) {
                'sum' => $annual * $this->windowWeight($w, $months, $m),
                'average' => $annual,
                default => $base + ($annual - $base) * $m / 12,
            }, $dec);
        }
        if ($agg === 'sum') {
            $out[12] = round($out[12] + $annual - array_sum($out), $dec);
        }

        return $out;
    }

    /** Realisasi 2025: total/rata-rata/nilai Desember persis sama dengan laporan tahunan. */
    protected function actualSeries2025(string $agg, ?float $base, float $annual, int $dec, array $months, int $seed): array
    {
        mt_srand($seed);
        $w = $this->season()[2025];
        $base ??= $annual;
        $out = [];
        foreach ($months as $m) {
            $noise = 1 + mt_rand(-250, 250) / 10000;
            $out[$m] = match ($agg) {
                'sum' => $annual * $this->windowWeight($w, $months, $m) * $noise,
                'average' => $annual * $noise,
                default => ($base + ($annual - $base) * $m / 12) * (1 + (mt_rand(-80, 80) / 10000)),
            };
        }
        if ($agg === 'sum') {
            $factor = $annual / array_sum($out);
            $out = array_map(fn ($v) => round($v * $factor, $dec), $out);
            $out[12] = round($out[12] + $annual - array_sum($out), $dec);
        } elseif ($agg === 'average') {
            $shift = $annual - array_sum($out) / count($out);
            $out = array_map(fn ($v) => round($v + $shift, $dec), $out);
            $out[12] = round($out[12] + ($annual * count($out) - array_sum($out)), $dec);
        } else {
            $out = array_map(fn ($v) => round($v, $dec), $out);
            $out[12] = round($annual, $dec);
        }

        return $out;
    }

    protected function actual(Kpi $kpi, int $year, int $m, float $value, bool $fromLt, bool $fin, string $owner): void
    {
        $next = fn (int $day) => sprintf('%d-%02d-%02d', $m === 12 ? $year + 1 : $year, $m === 12 ? 1 : $m + 1, $day);
        $row = ['kpi_id' => $kpi->id, 'month' => $m, 'actual' => $value, 'submitted_by' => $this->id($owner), 'submitted_at' => $next(5).' 10:00:00'];
        if ($year === 2025) {
            $row['is_projection'] = ! ($fromLt && $m === 12 && $kpi->aggregation !== 'sum');
            $row['notes'] = match (true) {
                $fromLt && $m === 12 => 'Nilai tahunan mengacu pada '.static::LT.' (data demo).',
                $fromLt => 'Rincian periodik ilustratif (data demo); total/nilai akhir tahun mengacu pada '.static::LT.'.',
                default => 'Data ilustrasi untuk pembelajaran (data demo).',
            };
            $row['evidence'] = $fromLt ? static::LT : null;
        } else {
            $row['is_projection'] = true;
            $row['notes'] = 'Data simulasi 2026 untuk pembelajaran (data demo).';
        }

        $returned = $this->returnedSeptember();
        $approved = ['status' => 'disetujui', 'verified_by' => $this->id('cfo'), 'verified_at' => $next(7).' 15:00:00', 'verification_note' => 'Sesuai laporan sumber.',
            'approved_by' => $this->id('dirut'), 'approved_at' => $next(8).' 11:00:00'];
        if ($year === 2025 || $m <= 8) {
            $row += $approved;
        } elseif ($returned && $kpi->code === $returned[0]) {
            $row += ['status' => 'dikembalikan', 'returned_by' => $this->id('cfo'), 'returned_at' => '2026-10-07 14:00:00', 'return_note' => $returned[1]];
        } elseif ($fin) {
            $row += ['status' => 'terverifikasi', 'verified_by' => $this->id('cfo'), 'verified_at' => '2026-10-07 15:00:00', 'verification_note' => 'Cocok dengan laporan keuangan September (belum diaudit).'];
        } else {
            $row += ['status' => 'draf'];
        }
        KpiActual::create($row);
    }

    // ------------------------------------------------------------------ inisiatif

    /** @return array<string,StrategicInitiative> */
    protected function initiatives(int $year, array $themes, array $obj): array
    {
        $data = $this->initiativeData($year);
        $ini = [];
        foreach ($data['defs'] as $k => [$code, $title, $desc, $th, $owner, $start, $end, $status, $imp, $urg, $risk, $bud, $objs, $outcome]) {
            $ini[$k] = StrategicInitiative::create(['code' => $code, 'title' => $title, 'description' => $desc, 'strategic_theme_id' => $themes[$th]->id,
                'owner_id' => $this->id($owner), 'start_date' => $start, 'end_date' => $end, 'status' => $status, 'impact' => $imp, 'urgency' => $urg,
                'risk' => $risk, 'budget_level' => $bud, 'expected_outcome' => $outcome]);
            $ini[$k]->objectives()->sync(collect($objs)->map(fn ($x) => $obj[$x]->id));
        }

        $asOf = $year === 2025 ? '2025-12-31' : '2026-10-15';
        foreach ($data['activities'] as [$k, $name, $owner, $start, $due, $prog]) {
            InitiativeActivity::create(['strategic_initiative_id' => $ini[$k]->id, 'name' => $name, 'owner_id' => $this->id($owner), 'start_date' => $start, 'due_date' => $due,
                'weight' => 1, 'progress' => $prog, 'status' => $prog >= 100 ? 'selesai' : ($prog > 0 ? 'berjalan' : 'belum'),
                'completed_at' => $prog >= 100 ? $due : null, 'last_update_note' => $prog >= 100 ? 'Selesai.' : 'Pembaruan progres per '.$asOf.'.', 'progress_updated_at' => $asOf.' 09:00:00']);
        }
        foreach ($data['milestones'] as [$k, $name, $due, $done]) {
            InitiativeMilestone::create(['strategic_initiative_id' => $ini[$k]->id, 'name' => $name, 'due_date' => $due, 'is_achieved' => $done,
                'achieved_at' => $done ? $due : null, 'owner_id' => $ini[$k]->owner_id, 'weight' => 1]);
        }
        foreach ($data['budgets'] as $b) { // inisiatif, item, kategori, rencana, realisasi, catatan, sumber dana
            [$k, $item, $cat, $plan, $real, $note] = $b;
            InitiativeBudget::create(['strategic_initiative_id' => $ini[$k]->id, 'item' => $item, 'category' => $cat, 'planned_amount' => $plan, 'realized_amount' => $real,
                'realization_date' => $real > 0 ? $asOf : null, 'funding_source' => $b[6] ?? ($cat === 'capex' ? 'Kas internal' : 'Anggaran operasional'),
                'notes' => $note ?? 'Angka ilustrasi untuk demo.',
                'verified_by' => $real > 0 ? $this->id('cfo') : null, 'verified_at' => $real > 0 ? $asOf.' 15:00:00' : null]);
        }
        foreach ($ini as $i) {
            $i->update(['budget_planned' => $i->budgets()->sum('planned_amount')]);
            $i->recalculateProgress();
        }

        return $ini;
    }

    // ------------------------------------------------------------------ temuan & tindakan korektif

    protected function findings(int $year, array $kpis, array $ini, array $reviews): void
    {
        foreach ($this->findingData($year) as [$title, $desc, $source, $rev, $persp, $sev, $amount, $kpi, $iniKey, $raiser, $date, $actions, $close]) {
            $finding = Finding::create(['title' => $title, 'description' => $desc, 'source' => $source, 'performance_review_id' => $rev ? ($reviews[$rev]->id ?? null) : null,
                'perspective' => $persp, 'severity' => $sev, 'amount' => $amount, 'kpi_id' => $kpis[$kpi]->id ?? null,
                'strategic_initiative_id' => $iniKey ? ($ini[$iniKey]->id ?? null) : null, 'raised_by' => $this->id($raiser), 'raised_at' => $date, 'status' => 'terbuka']);
            foreach ($actions as [$at, $owner, $due, $st, $prog]) {
                CorrectiveAction::create(['finding_id' => $finding->id, 'title' => $at, 'owner_id' => $this->id($owner), 'due_date' => $due, 'status' => $st, 'progress' => $prog,
                    'result_note' => $st === 'selesai' ? 'Selesai — bukti di laporan unit.' : null]);
            }
            $finding->refresh()->syncStatus();
            if ($close) { // [tanggal tutup, catatan penutupan]
                $finding->forceFill(['status' => 'ditutup', 'closed_by' => $this->id('dirut'), 'closed_at' => $close[0].' 16:00:00', 'closure_note' => $close[1]])->saveQuietly();
            }
        }
    }

    // ------------------------------------------------------------------ kesinambungan

    protected function continuity(int $year, array $ini, array $obj): void
    {
        $a = MaturityAssessment::create(['title' => 'Penilaian Kematangan Manajemen Strategi '.$year, 'assessment_date' => $year === 2025 ? '2025-12-15' : '2026-09-30',
            'assessor_id' => $this->id('strategi'), 'status' => 'final',
            'notes' => $year === 2025 ? 'Baseline sebelum memakai Kinera.' : 'Penilaian ulang setelah 9 bulan memakai Kinera (ilustrasi).']);
        $bump = $year === 2025 ? 0 : 1;
        $pattern = $this->maturityPattern();
        foreach (MaturityQuestion::orderBy('sort')->get() as $i => $q) {
            $score = min(5, max(1, $pattern[$i % count($pattern)] + $bump - ($i % 7 === 0 ? 1 : 0)));
            MaturityAnswer::create(['maturity_assessment_id' => $a->id, 'maturity_question_id' => $q->id, 'score' => $score, 'evidence' => $i % 4 === 0 ? 'Bukti: notulen rapat & laporan Kinera.' : null]);
        }
        app(MaturityScoringService::class)->recalculate($a);

        $data = $this->continuityData($year);
        foreach ($data['dependencies'] as [$title, $type, $iniKey, $objKey, $dep, $desc, $owner, $risk, $mit]) {
            StrategicDependency::create(['title' => $title, 'dependency_type' => $type, 'strategic_initiative_id' => $ini[$iniKey]->id, 'strategic_objective_id' => $obj[$objKey]->id,
                'depends_on' => $dep, 'description' => $desc, 'owner_id' => $this->id($owner), 'risk_level' => $risk, 'mitigation' => $mit, 'status' => 'aktif']);
        }
        foreach ($data['institutionalizations'] as [$title, $type, $desc, $freq, $owner, $status]) {
            Institutionalization::create(['title' => $title, 'mechanism_type' => $type, 'description' => $desc, 'frequency' => $freq, 'owner_id' => $this->id($owner),
                'status' => $status, 'evidence' => $status === 'rencana' ? null : 'Notulen & laporan Kinera.', 'started_at' => $year.'-01-15',
                'next_review_date' => $year === 2025 ? '2026-01-15' : '2026-12-15']);
        }
    }
}
