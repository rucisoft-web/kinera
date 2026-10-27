<?php

namespace App\Services;

use App\Models\StrategicInitiative;
use Illuminate\Support\Collection;

/** Matriks Nilai (dampak x 2 + urgensi) x Upaya (risiko + tingkat anggaran). */
class InitiativePortfolioService
{
    public const VALUE_THRESHOLD = 10; // skala 3..15

    public const EFFORT_THRESHOLD = 6;  // skala 2..10

    public const QUADRANTS = [
        'quick_win' => ['label' => 'Quick Win', 'hint' => 'Nilai tinggi, upaya rendah — kerjakan segera', 'color' => '#2E7D4F'],
        'prioritas' => ['label' => 'Prioritas Strategis', 'hint' => 'Nilai tinggi, upaya tinggi — kawal ketat', 'color' => '#1F4E8C'],
        'pengisi' => ['label' => 'Pengisi', 'hint' => 'Nilai rendah, upaya rendah — kerjakan bila ada kapasitas', 'color' => '#C88A12'],
        'tinjau_ulang' => ['label' => 'Tinjau Ulang', 'hint' => 'Nilai rendah, upaya tinggi — pertimbangkan ulang', 'color' => '#D32F2F'],
    ];

    public function __construct(protected InitiativeProgressService $progress) {}

    public function value(StrategicInitiative $i): int
    {
        return (int) $i->impact * 2 + (int) $i->urgency;
    }

    public function effort(StrategicInitiative $i): int
    {
        return (int) $i->risk + (int) $i->budget_level;
    }

    public function quadrant(StrategicInitiative $i): string
    {
        $highValue = $this->value($i) >= self::VALUE_THRESHOLD;
        $highEffort = $this->effort($i) > self::EFFORT_THRESHOLD;

        return match (true) {
            $highValue && ! $highEffort => 'quick_win',
            $highValue && $highEffort => 'prioritas',
            ! $highValue && ! $highEffort => 'pengisi',
            default => 'tinjau_ulang',
        };
    }

    public function portfolio(): Collection
    {
        return StrategicInitiative::with(['owner', 'budgets', 'milestones', 'objectives'])
            ->whereNotIn('status', ['dibatalkan'])
            ->orderBy('code')->get()
            ->map(function (StrategicInitiative $i) {
                $next = $i->milestones->where('is_achieved', false)->sortBy('due_date')->first();

                return [
                    'initiative' => $i,
                    'value' => $this->value($i),
                    'effort' => $this->effort($i),
                    'quadrant' => $this->quadrant($i),
                    'elapsed' => $this->progress->timeElapsed($i),
                    'late' => $this->progress->isLate($i),
                    'absorption' => $this->progress->budgetAbsorption($i),
                    'realized' => (float) $i->budgets->sum('realized_amount'),
                    'planned' => (float) $i->budgets->sum('planned_amount') ?: (float) $i->budget_planned,
                    'next_milestone' => $next,
                ];
            });
    }
}
