<?php

namespace App\Services;

use App\Models\Kpi;
use Illuminate\Support\Collection;

/**
 * Skor KPI: higher-is-better = aktual/target x 100, lower-is-better = target/aktual x 100.
 * Status hijau >= 100, kuning >= 80, merah di bawahnya.
 */
class KpiScoringService
{
    public function score(?Kpi $kpi, ?float $actual, ?float $target): ?float
    {
        if (! $kpi || $actual === null || $target === null) {
            return null;
        }
        $cap = (float) config('dhastram.score_cap', 120);
        if ($kpi->direction === 'lower') {
            if ($actual <= 0) {
                return $target <= 0 ? 100.0 : $cap;
            }
            $score = $target / $actual * 100;
        } else {
            if ($target == 0.0) {
                return $actual >= 0 ? 100.0 : 0.0;
            }
            $score = $actual / $target * 100;
        }

        return round(max(0, min($cap, $score)), 1);
    }

    public function status(?float $score): ?string
    {
        if ($score === null) {
            return null;
        }

        return match (true) {
            $score >= config('dhastram.score_green', 100) => 'hijau',
            $score >= config('dhastram.score_yellow', 80) => 'kuning',
            default => 'merah',
        };
    }

    /**
     * Capaian KPI sampai bulan tertentu (YTD) sesuai jenis agregasi.
     *
     * @return array{actual:?float,target:?float,score:?float,status:?string,months:int,last_month:?int}
     */
    public function ytd(Kpi $kpi, ?int $uptoMonth = null): array
    {
        $actuals = $this->validActuals($kpi)->when($uptoMonth, fn ($c) => $c->where('month', '<=', $uptoMonth));
        $targets = $kpi->relationLoaded('targets') ? $kpi->targets : $kpi->targets()->get();
        $months = $actuals->pluck('month')->all();
        if ($months === []) {
            return ['actual' => null, 'target' => null, 'score' => null, 'status' => null, 'months' => 0, 'last_month' => null];
        }
        $t = $targets->whereIn('month', $months);
        $lastMonth = max($months);
        [$actual, $target] = match ($kpi->aggregation) {
            'average' => [$actuals->avg('actual'), $t->avg('target')],
            'last' => [(float) $actuals->firstWhere('month', $lastMonth)->actual, optional($targets->firstWhere('month', $lastMonth))->target],
            default => [$actuals->sum('actual'), $t->sum('target')],
        };
        $actual = $actual === null ? null : (float) $actual;
        $target = $target === null ? null : (float) $target;
        $score = $this->score($kpi, $actual, $target);

        return ['actual' => $actual, 'target' => $target, 'score' => $score, 'status' => $this->status($score), 'months' => count($months), 'last_month' => $lastMonth];
    }

    /** Realisasi yang dihitung: semua kecuali yang dikembalikan. */
    public function validActuals(Kpi $kpi): Collection
    {
        $actuals = $kpi->relationLoaded('actuals') ? $kpi->actuals : $kpi->actuals()->get();

        return $actuals->where('status', '!=', 'dikembalikan')->values();
    }

    /** @return array<int,array{actual:?float,target:?float,score:?float,status:?string}> */
    public function monthly(Kpi $kpi): array
    {
        $out = [];
        $actuals = $this->validActuals($kpi)->keyBy('month');
        $targets = ($kpi->relationLoaded('targets') ? $kpi->targets : $kpi->targets()->get())->keyBy('month');
        for ($m = 1; $m <= 12; $m++) {
            $a = $actuals->get($m)?->actual;
            $t = $targets->get($m)?->target;
            $s = $this->score($kpi, $a === null ? null : (float) $a, $t === null ? null : (float) $t);
            $out[$m] = ['actual' => $a, 'target' => $t === null ? null : (float) $t, 'score' => $s, 'status' => $this->status($s)];
        }

        return $out;
    }
}
