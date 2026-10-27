<?php

namespace App\Services;

use App\Models\Finding;
use App\Models\StrategicInitiative;
use App\Support\BscContext;

/** Pusat Kendali Eksekutif: skor strategis, capaian perspektif, sasaran merah/kuning, IK kritis, inisiatif terlambat. */
class ExecutiveDashboardService
{
    public function __construct(
        protected StrategicScoreService $score,
        protected InitiativeProgressService $progress,
        protected BscContext $ctx,
    ) {}

    public function data(): array
    {
        $objectives = $this->score->objectives();
        $perspectives = $this->score->perspectives($objectives);
        $overall = $this->score->overall($perspectives);
        $kpiRows = $objectives->flatMap(fn ($o) => $o['kpis']->map(fn ($k) => $k + ['objective' => $o['objective']]));

        $initiatives = StrategicInitiative::with(['owner', 'budgets', 'milestones'])->whereNotIn('status', ['selesai', 'dibatalkan'])->get();
        $late = $initiatives->filter(fn ($i) => $this->progress->isLate($i))->map(fn ($i) => [
            'initiative' => $i, 'elapsed' => $this->progress->timeElapsed($i),
        ])->values();

        return [
            'overall' => $overall,
            'perspectives' => $perspectives,
            'redYellow' => $objectives->filter(fn ($o) => in_array($o['status'], ['merah', 'kuning'], true))->sortBy('score')->values(),
            'criticalKpis' => $kpiRows->filter(fn ($r) => $r['kpi']->is_critical || $r['status'] === 'merah')->sortBy(fn ($r) => $r['score'] ?? 999)->values(),
            'kpiStats' => [
                'hijau' => $kpiRows->where('status', 'hijau')->count(),
                'kuning' => $kpiRows->where('status', 'kuning')->count(),
                'merah' => $kpiRows->where('status', 'merah')->count(),
                'kosong' => $kpiRows->whereNull('status')->count(),
                'total' => $kpiRows->count(),
            ],
            'lateInitiatives' => $late,
            'initiativeCount' => $initiatives->count(),
            'openFindings' => Finding::where('status', '!=', 'ditutup')->count(),
            'lastMonth' => $this->ctx->lastClosedMonth(),
        ];
    }
}
