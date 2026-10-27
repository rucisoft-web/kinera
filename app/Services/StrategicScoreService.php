<?php

namespace App\Services;

use App\Models\StrategicObjective;
use App\Support\Terms;
use Illuminate\Support\Collection;

/** Capaian sasaran tertimbang dari bobot IK, capaian perspektif, dan skor strategis. */
class StrategicScoreService
{
    public function __construct(protected KpiScoringService $kpiScoring) {}

    /** @var array<int,array>|null */
    protected ?array $cache = null;

    public function objectives(?int $uptoMonth = null): Collection
    {
        $objectives = StrategicObjective::with(['kpis' => fn ($q) => $q->where('is_active', true), 'kpis.targets', 'kpis.actuals', 'kpis.owner', 'owner', 'theme'])
            ->orderBy('perspective')->orderBy('sort')->get();

        return $objectives->map(function (StrategicObjective $o) use ($uptoMonth) {
            $kpis = $o->kpis->map(function ($k) use ($uptoMonth) {
                $ytd = $this->kpiScoring->ytd($k, $uptoMonth);

                return ['kpi' => $k] + $ytd;
            });
            $scored = $kpis->whereNotNull('score');
            $weight = $scored->sum(fn ($r) => $r['kpi']->weight ?: 1);
            $score = $weight > 0 ? round($scored->sum(fn ($r) => $r['score'] * ($r['kpi']->weight ?: 1)) / $weight, 1) : null;

            return [
                'objective' => $o,
                'kpis' => $kpis,
                'score' => $score,
                'status' => $this->kpiScoring->status($score),
            ];
        });
    }

    /** @return array<string,array{label:string,score:?float,status:?string,objectives:int,color:string}> */
    public function perspectives(?Collection $objectives = null): array
    {
        $objectives ??= $this->objectives();
        $out = [];
        foreach (Terms::perspectives() as $key => $label) {
            $rows = $objectives->filter(fn ($r) => $r['objective']->perspective === $key && $r['score'] !== null);
            $w = $rows->sum(fn ($r) => $r['objective']->weight ?: 1);
            $score = $w > 0 ? round($rows->sum(fn ($r) => $r['score'] * ($r['objective']->weight ?: 1)) / $w, 1) : null;
            $out[$key] = [
                'label' => $label,
                'score' => $score,
                'status' => $this->kpiScoring->status($score),
                'objectives' => $objectives->filter(fn ($r) => $r['objective']->perspective === $key)->count(),
                'color' => Terms::perspectiveColor($key),
            ];
        }

        return $out;
    }

    public function overall(?array $perspectives = null): array
    {
        $perspectives ??= $this->perspectives();
        $weights = config('dhastram.perspective_weights');
        $sum = 0;
        $w = 0;
        foreach ($perspectives as $key => $p) {
            if ($p['score'] !== null) {
                $sum += $p['score'] * ($weights[$key] ?? 25);
                $w += $weights[$key] ?? 25;
            }
        }
        $score = $w > 0 ? round($sum / $w, 1) : null;

        return ['score' => $score, 'status' => $this->kpiScoring->status($score)];
    }

    /** Tren skor strategis per bulan (berdasarkan realisasi kumulatif). */
    public function trend(int $uptoMonth): array
    {
        $out = [];
        for ($m = 1; $m <= $uptoMonth; $m++) {
            $obj = $this->objectives($m);
            $per = $this->perspectives($obj);
            $out[$m] = ['overall' => $this->overall($per)['score']] + collect($per)->map(fn ($p) => $p['score'])->all();
        }

        return $out;
    }
}
