<?php

namespace App\Services;

use App\Models\AnalysisPeriod;
use App\Models\Kpi;
use App\Models\KpiActual;
use App\Models\PerformanceContract;
use App\Support\BscContext;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/**
 * Kontrak Kinerja Pemimpin BLU menurut PER-6/PB/2025: target trajektori, perhitungan capaian per Indikator Kinerja
 * (format Lampiran VI), uji syarat insentif kelebihan capaian (Pasal 14 ayat 4), dan tenggat siklus Kontrak Kinerja.
 */
class BluContractService
{
    public const QUARTERS = [3 => 'Triwulan I', 6 => 'Triwulan II', 9 => 'Triwulan III', 12 => 'Triwulan IV'];

    public function __construct(protected KpiScoringService $scoring, protected BscContext $ctx) {}

    /** @return Collection<int,Kpi> Indikator Kinerja aktif perioda berjalan, urut aspek lalu kode. */
    public function indicators(): Collection
    {
        $order = array_flip(array_keys(Kpi::ASPECTS));

        return Kpi::with(['targets', 'actuals', 'owner', 'objective'])->where('is_active', true)->get()
            ->sortBy(fn (Kpi $k) => sprintf('%02d-%s', $order[$k->aspect] ?? 9, $k->code))->values();
    }

    /** Kontrak yang berlaku: versi tertinggi yang sudah ditandatangani, atau versi terakhir bila belum ada yang ditandatangani. */
    public function activeContract(): ?PerformanceContract
    {
        return PerformanceContract::where('status', 'ditandatangani')->orderByDesc('version')->first()
            ?? PerformanceContract::orderByDesc('version')->first();
    }

    /** Target kumulatif s.d. akhir setiap triwulan (nilai trajektori Lampiran I) mengikuti jenis agregasi indikator. */
    public function quarterTargets(Kpi $kpi): array
    {
        $out = [];
        foreach (array_keys(self::QUARTERS) as $q) {
            $t = $kpi->targets->where('month', '<=', $q);
            if ($kpi->frequency === 'tahunan' && $q < 12 || $t->isEmpty()) {
                $out[$q] = null;

                continue;
            }
            $out[$q] = match ($kpi->aggregation) {
                'average' => round((float) $t->avg('target'), 4),
                'last' => (float) $t->sortBy('month')->last()->target,
                default => round((float) $t->sum('target'), 4),
            };
        }

        return $out;
    }

    /** Potret Indikator Kinerja untuk dokumen Kontrak Kinerja (disimpan di performance_contracts.indicators). */
    public function snapshot(?PerformanceContract $previous = null): array
    {
        $before = collect($previous?->indicators ?? [])->keyBy('code');

        return $this->indicators()->map(function (Kpi $k) use ($before) {
            $targets = $this->quarterTargets($k);
            $row = [
                'kpi_id' => $k->id, 'code' => $k->code, 'name' => $k->name, 'aspect' => $k->aspect, 'unit' => $k->unit,
                'direction' => $k->direction, 'decimals' => $k->decimals, 'frequency' => $k->frequency, 'mandatory' => $k->is_mandatory,
                'max' => $k->achievementCap(), 'targets' => $targets, 'annual' => $k->annual_target ?? $targets[12],
                'definition' => $k->operational_definition, 'formula' => $k->formula, 'source' => $k->data_source,
                'collection' => $k->data_collection, 'test' => $k->test_method, 'documents' => $k->supporting_documents,
            ];
            if ($before->isNotEmpty()) {
                $old = $before->get($k->code);
                $oldAnnual = $old['annual'] ?? null;
                $row['change'] = match (true) {
                    $old === null => 'baru',
                    $oldAnnual !== null && $row['annual'] !== null && $this->isEasier($k->direction, (float) $row['annual'], (float) $oldAnnual) => 'target_turun',
                    $oldAnnual != $row['annual'] || $old['name'] !== $row['name'] => 'diubah',
                    default => null,
                };
                $row['previous_annual'] = $oldAnnual;
            }

            return $row;
        })->all();
    }

    protected function isEasier(string $direction, float $new, float $old): bool
    {
        return $direction === 'lower' ? $new > $old + 1e-9 : $new < $old - 1e-9;
    }

    /** Capaian mentah (tanpa batas) realisasi terhadap target. */
    public function rawScore(Kpi $kpi, ?float $actual, ?float $target): ?float
    {
        if ($actual === null || $target === null) {
            return null;
        }
        if ($kpi->direction === 'lower') {
            return $actual <= 0 ? ($target <= 0 ? 100.0 : null) : round($target / $actual * 100, 2);
        }

        return $target == 0.0 ? 100.0 : round($actual / $target * 100, 2);
    }

    /**
     * Perhitungan capaian per Indikator Kinerja s.d. bulan tertentu (format lampiran Lampiran VI):
     * target, realisasi, capaian, capaian maksimum, capaian diakui, dan penanda peringatan.
     */
    public function achievements(int $upto = 12, ?PerformanceContract $contract = null): array
    {
        $contract ??= $this->activeContract();
        $lowered = $contract?->loweredCodes() ?? [];
        $rows = [];
        foreach ($this->indicators() as $k) {
            $ytd = $this->scoring->ytd($k, $upto);
            $raw = $this->rawScore($k, $ytd['actual'], $ytd['target']);
            $cap = in_array($k->code, $lowered, true) ? min(100.0, $k->achievementCap()) : $k->achievementCap();
            $recognized = $raw === null ? null : round(max(0, min($cap, $raw)), 2);
            $rows[] = [
                'kpi' => $k, 'target' => $ytd['target'], 'actual' => $ytd['actual'], 'raw' => $raw, 'cap' => $cap, 'recognized' => $recognized,
                'status' => $this->scoring->status($recognized), 'last_month' => $ytd['last_month'],
                'lowered' => in_array($k->code, $lowered, true),
                'below_floor' => $recognized !== null && $recognized < (float) config('bsc.score_floor', 65),
                'mandatory_unmet' => $k->is_mandatory && $raw !== null && $raw < 100,
                'pending' => $k->actuals->where('month', '<=', $upto)->whereIn('status', ['draf', 'terverifikasi'])->count(),
            ];
        }
        $scored = collect($rows)->whereNotNull('recognized');

        return [
            'rows' => $rows,
            'total' => round((float) $scored->sum('recognized'), 2),
            'average' => $scored->isEmpty() ? null : round((float) $scored->avg('recognized'), 2),
            'count' => $scored->count(),
            'by_aspect' => collect($rows)->groupBy(fn ($r) => $r['kpi']->aspect)
                ->map(fn ($g) => ['count' => $g->count(), 'average' => $g->whereNotNull('recognized')->isEmpty() ? null : round((float) $g->whereNotNull('recognized')->avg('recognized'), 2)])->all(),
            'below_floor' => collect($rows)->where('below_floor', true)->values()->all(),
            'mandatory_unmet' => collect($rows)->where('mandatory_unmet', true)->values()->all(),
            'contract' => $contract,
            'upto' => $upto,
        ];
    }

    /** Capaian per triwulan setiap indikator (laporan Capaian Kinerja per trajektori, Pasal 10). */
    public function quarterly(): array
    {
        $out = [];
        foreach ($this->indicators() as $k) {
            $row = ['kpi' => $k, 'q' => []];
            foreach (array_keys(self::QUARTERS) as $q) {
                $hasData = $k->actuals->where('month', '<=', $q)->where('month', '>', $q - 3)->where('status', '!=', 'dikembalikan')->isNotEmpty();
                $ytd = $hasData ? $this->scoring->ytd($k, $q) : null;
                $raw = $ytd ? $this->rawScore($k, $ytd['actual'], $ytd['target']) : null;
                $row['q'][$q] = ['target' => $this->quarterTargets($k)[$q], 'actual' => $ytd['actual'] ?? null,
                    'score' => $raw === null ? null : round(max(0, min($k->achievementCap(), $raw)), 2)];
            }
            $out[] = $row;
        }

        return $out;
    }

    /**
     * Uji syarat permohonan insentif kinerja atas kelebihan capaian (Pasal 14 ayat 4) dan ketepatan waktu (Pasal 17 ayat 4 huruf c).
     *
     * @return list<array{key:string,label:string,ref:string,status:string,note:string}>
     */
    public function incentiveChecks(?array $achievements = null): array
    {
        $a = $achievements ?? $this->achievements();
        $contract = $a['contract'];
        $role = fn (string $r) => collect($a['rows'])->first(fn ($row) => $row['kpi']->incentive_role === $r);
        $flag = fn (?bool $ok) => $ok === null ? 'belum' : ($ok ? 'ok' : 'gagal');
        $codes = fn (array $rows) => collect($rows)->map(fn ($r) => $r['kpi']->code)->join(', ');

        $pnbp = $role('pnbp');
        $ebitda = $role('ebitda');
        $opini = $role('opini');
        $deadlines = collect($this->deadlines());
        $late = $deadlines->whereIn('key', ['usulan', 'tandatangan', 'perubahan_usul'])->where('status', 'terlambat');

        return [
            ['key' => 'a', 'label' => 'Total capaian kinerja melebihi target', 'ref' => 'Pasal 14 (4) a',
                'status' => $flag($a['average'] === null ? null : $a['average'] > 100),
                'note' => $a['average'] === null ? 'Belum ada capaian.' : 'Rata-rata capaian diakui '.number_format($a['average'], 2, ',', '.').'% dari '.$a['count'].' indikator.'],
            ['key' => 'b', 'label' => 'Seluruh indikator wajib (mandatory) tercapai', 'ref' => 'Pasal 14 (4) b',
                'status' => $flag(collect($a['rows'])->where('kpi.is_mandatory', true)->isEmpty() ? null : $a['mandatory_unmet'] === []),
                'note' => $a['mandatory_unmet'] === [] ? 'Semua indikator wajib ≥ 100%.' : 'Belum tercapai: '.$codes($a['mandatory_unmet']).'.'],
            ['key' => 'c', 'label' => 'Tidak ada indikator dengan capaian di bawah '.config('bsc.score_floor', 65).'%', 'ref' => 'Pasal 14 (4) c',
                'status' => $flag($a['count'] === 0 ? null : $a['below_floor'] === []),
                'note' => $a['below_floor'] === [] ? 'Tidak ada indikator di bawah ambang.' : 'Di bawah ambang: '.$codes($a['below_floor']).'.'],
            ['key' => 'd', 'label' => 'Target penerimaan negara bukan pajak (PNBP) tercapai', 'ref' => 'Pasal 14 (4) d',
                'status' => $flag($pnbp && $pnbp['raw'] !== null ? $pnbp['raw'] >= 100 : null),
                'note' => $pnbp ? ($pnbp['raw'] === null ? 'Realisasi '.$pnbp['kpi']->code.' belum ada.' : $pnbp['kpi']->code.' tercapai '.number_format($pnbp['raw'], 2, ',', '.').'% dari target.') : 'Tandai satu indikator sebagai "Target PNBP".'],
            ['key' => 'e', 'label' => 'Memiliki surplus EBITDA', 'ref' => 'Pasal 14 (4) e',
                'status' => $flag($ebitda && $ebitda['actual'] !== null ? $ebitda['actual'] > 0 : null),
                'note' => $ebitda ? ($ebitda['actual'] === null ? 'Realisasi '.$ebitda['kpi']->code.' belum ada.' : $ebitda['kpi']->code.' = '.\App\Support\Fmt::kpiValue($ebitda['actual'], $ebitda['kpi']->unit, $ebitda['kpi']->decimals).'.') : 'Tandai satu indikator sebagai "Surplus EBITDA".'],
            ['key' => 'f', 'label' => 'Laporan keuangan audited beropini Wajar Tanpa Pengecualian', 'ref' => 'Pasal 14 (4) f',
                'status' => $flag($opini && $opini['actual'] !== null ? $opini['actual'] >= 4 : null),
                'note' => $opini ? ($opini['actual'] === null ? 'Opini audit tahun ini belum terbit.' : 'Skor opini '.(int) $opini['actual'].' (4 = WTP).') : 'Tandai satu indikator sebagai "Opini laporan keuangan".'],
            ['key' => 'g', 'label' => 'Bebas kasus hukum berputusan pengadilan tingkat pertama', 'ref' => 'Pasal 14 (4) g',
                'status' => $flag($contract?->legal_case_free), 'note' => $contract?->legal_case_free === null ? 'Isi pernyataan pada Kontrak Kinerja.' : ($contract->legal_case_free ? 'Dinyatakan bebas kasus hukum.' : 'Terdapat kasus hukum.')],
            ['key' => 'h', 'label' => 'Persyaratan lain Pembina Teknis/Pembina Keuangan', 'ref' => 'Pasal 14 (4) h',
                'status' => $flag($contract?->other_requirements_met), 'note' => $contract?->incentive_notes ?: 'Isi pada Kontrak Kinerja.'],
            ['key' => 'waktu', 'label' => 'Usulan & penyampaian Kontrak Kinerja tepat waktu', 'ref' => 'Pasal 6 (4), 8 (5), 17 (4) c',
                'status' => $late->isNotEmpty() ? 'gagal' : ($deadlines->whereIn('key', ['usulan', 'tandatangan'])->where('status', 'tepat waktu')->count() === 2 ? 'ok' : 'belum'),
                'note' => $late->isNotEmpty() ? 'Terlambat: '.$late->pluck('title')->join('; ').'.' : 'Tidak ada keterlambatan tercatat.'],
        ];
    }

    /**
     * Kalender tenggat PER-6/PB/2025 untuk tahun kontrak perioda aktif.
     *
     * @return list<array{key:string,title:string,ref:string,due:CarbonImmutable,done:?CarbonImmutable,status:string,optional:bool}>
     */
    public function deadlines(?AnalysisPeriod $period = null, ?CarbonImmutable $today = null): array
    {
        $period ??= $this->ctx->period();
        if (! $period) {
            return [];
        }
        $year = (int) $period->year;
        $today ??= CarbonImmutable::now()->startOfDay();
        $cfg = config('bsc.blu');
        $date = fn (int $y, string $md) => CarbonImmutable::parse($y.'-'.$md);
        $contracts = PerformanceContract::withoutGlobalScopes()->where('analysis_period_id', $period->id)->orderBy('version')->get();
        $first = $contracts->firstWhere('version', 0);
        $change = $contracts->where('kind', 'perubahan')->sortByDesc('version')->first();

        $items = [
            ['usulan', 'Usulan Kontrak Kinerja (dengan DO & persetujuan Pembina Teknis) ke DJPb', 'Pasal 6 (2)', $this->lastWorkday($date($year - 1, '12-31')), $first?->proposed_at, false],
            ['kesepakatan', 'Pembahasan & kesepakatan (Berita Acara, Lampiran II)', 'Pasal 7 (2)–(3)', $this->workdayOnOrBefore($date($year, $cfg['agreement_deadline'])), $first?->discussed_at, false],
            ['tandatangan', 'Kontrak Kinerja ditandatangani Pemimpin BLU & diterima DJPb', 'Pasal 8 (2)', $date($year, $cfg['signing_deadline']), $first?->leader_signed_at, false],
            ['dirjen', 'Kontrak Kinerja ditandatangani Dirjen Perbendaharaan', 'Pasal 9 (1)', $date($year, $cfg['signing_deadline']), $first?->dg_signed_at, false],
        ];
        foreach (self::QUARTERS as $q => $label) {
            $items[] = ['lapor'.$q, 'Laporan Capaian Kinerja '.$label.' ke DJPb & Pembina Teknis', 'Pasal 10 (1)',
                $this->addWorkdays($date($year, sprintf('%02d', $q).'-01')->endOfMonth()->startOfDay(), (int) $cfg['report_workdays']), $this->reportedAt($period, $q), false];
        }
        $items[] = ['perubahan_usul', 'Batas usulan perubahan Kontrak Kinerja', 'Pasal 19 (2)', $date($year, $cfg['change_proposal_deadline']), $change?->proposed_at, true];
        $items[] = ['perubahan_ttd', 'Kontrak Kinerja perubahan ditetapkan & ditandatangani', 'Pasal 19 (7)', $date($year, $cfg['change_signing_deadline']), $change?->dg_signed_at, true];
        $items[] = ['insentif', 'Permohonan insentif kelebihan capaian (setelah LK audited)', 'Pasal 14 (5)', $date($year + 1, $cfg['incentive_request_deadline']), null, true];

        return collect($items)->map(function ($i) use ($today, $change) {
            [$key, $title, $ref, $due, $done, $optional] = $i;
            $done = $done ? CarbonImmutable::parse($done)->startOfDay() : null;
            $status = match (true) {
                $done !== null => $done->lte($due) ? 'tepat waktu' : 'terlambat',
                $optional && $key === 'perubahan_ttd' && $change === null => 'tidak ada perubahan',
                $optional && $key === 'perubahan_usul' && $today->gt($due) => 'tidak ada perubahan',
                $key === 'insentif' && $today->gt($due) => 'tidak diajukan',
                $today->gt($due) => 'lewat tenggat',
                $today->diffInDays($due) <= 30 => 'segera',
                default => 'akan datang',
            };

            return compact('key', 'title', 'ref', 'due', 'done', 'status', 'optional');
        })->all();
    }

    /** Tanggal laporan triwulan dianggap tersampaikan: saat semua realisasi bulan jatuh tempo triwulan itu disetujui. */
    protected function reportedAt(AnalysisPeriod $period, int $q): ?string
    {
        $due = KpiActual::withoutGlobalScopes()->where('analysis_period_id', $period->id)->where('month', $q)->get();
        $kpiCount = Kpi::withoutGlobalScopes()->where('analysis_period_id', $period->id)->where('is_active', true)
            ->get()->filter(fn (Kpi $k) => $k->isDueIn($q))->count();
        if ($kpiCount === 0 || $due->where('status', 'disetujui')->count() < $kpiCount) {
            return null;
        }

        return (string) $due->max('approved_at');
    }

    public function addWorkdays(CarbonImmutable $from, int $days): CarbonImmutable
    {
        $d = $from;
        while ($days > 0) {
            $d = $d->addDay();
            if (! $d->isWeekend()) {
                $days--;
            }
        }

        return $d;
    }

    public function lastWorkday(CarbonImmutable $d): CarbonImmutable
    {
        return $this->workdayOnOrBefore($d);
    }

    /** Pasal 7 ayat (3): bila jatuh pada hari libur, maju ke hari kerja terakhir sebelumnya (libur nasional belum diperhitungkan). */
    public function workdayOnOrBefore(CarbonImmutable $d): CarbonImmutable
    {
        while ($d->isWeekend()) {
            $d = $d->subDay();
        }

        return $d;
    }
}
