<?php

namespace App\Services;

use App\Models\StrategicInitiative;
use Carbon\Carbon;

/** Progres inisiatif dari aktivitas (70%) dan milestone (30%), dibandingkan waktu berjalan. */
class InitiativeProgressService
{
    public function calculate(StrategicInitiative $i): float
    {
        $acts = $i->activities()->get();
        $miles = $i->milestones()->get();
        $actProgress = null;
        if ($acts->isNotEmpty()) {
            $w = $acts->sum(fn ($a) => (float) $a->weight ?: 1);
            $actProgress = $w > 0 ? $acts->sum(fn ($a) => (float) $a->progress * ((float) $a->weight ?: 1)) / $w : 0;
        }
        $mileProgress = null;
        if ($miles->isNotEmpty()) {
            $w = $miles->sum(fn ($m) => (float) $m->weight ?: 1);
            $mileProgress = $w > 0 ? $miles->where('is_achieved', true)->sum(fn ($m) => (float) $m->weight ?: 1) / $w * 100 : 0;
        }
        $progress = match (true) {
            $actProgress !== null && $mileProgress !== null => $actProgress * 0.7 + $mileProgress * 0.3,
            $actProgress !== null => $actProgress,
            $mileProgress !== null => $mileProgress,
            default => (float) $i->progress,
        };

        return round(min(100, max(0, $progress)), 1);
    }

    public function timeElapsed(StrategicInitiative $i, ?Carbon $at = null): ?float
    {
        if (! $i->start_date || ! $i->end_date) {
            return null;
        }
        $at ??= today();
        $total = max(1, $i->start_date->diffInDays($i->end_date));
        $passed = $i->start_date->diffInDays($at, false);

        return round(min(100, max(0, $passed / $total * 100)), 1);
    }

    /** terlambat bila progres tertinggal > 10 poin dari waktu berjalan atau lewat tanggal akhir. */
    public function isLate(StrategicInitiative $i): bool
    {
        if (in_array($i->status, ['selesai', 'dibatalkan'], true)) {
            return false;
        }
        if ($i->end_date && $i->end_date->lt(today()) && $i->progress < 100) {
            return true;
        }
        $elapsed = $this->timeElapsed($i);

        return $elapsed !== null && ($elapsed - (float) $i->progress) > 10;
    }

    public function budgetAbsorption(StrategicInitiative $i): ?float
    {
        $planned = (float) $i->budgets->sum('planned_amount') ?: (float) $i->budget_planned;
        if ($planned <= 0) {
            return null;
        }

        return round($i->budgets->sum('realized_amount') / $planned * 100, 1);
    }
}
