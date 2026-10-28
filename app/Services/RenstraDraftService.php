<?php

namespace App\Services;

use App\Models\AtmanProfile;
use App\Models\Capability;
use App\Models\CoreValue;
use App\Models\Kpi;
use App\Models\ObjectiveRelation;
use App\Models\PestelAnalysis;
use App\Models\Regulation;
use App\Models\Stakeholder;
use App\Models\StrategicInitiative;
use App\Models\StrategicIssue;
use App\Models\StrategicObjective;
use App\Models\StrategicTheme;
use App\Models\SwotItem;
use App\Models\TowsStrategy;
use App\Support\BscContext;
use App\Support\Terms;

/**
 * Menyusun Draf Renstra lima tahunan dari isian Kinera pada periode aktif:
 * Bab I lingkungan strategis, Bab II jati diri, Bab III strategi, Bab IV sasaran & IKU,
 * Bab V program & pendanaan, serta lampiran matriks kinerja dan definisi operasional.
 * Target tahun ke-2 s.d. ke-5 dan pagu indikatif adalah usulan otomatis yang harus ditetapkan pimpinan.
 */
class RenstraDraftService
{
    /** Pertumbuhan pagu indikatif per tahun untuk matriks pendanaan. */
    public const BUDGET_GROWTH = 0.05;

    public function __construct(private BscContext $ctx) {}

    /** @return array<int> pilihan tahun awal Renstra (dua tahun ke belakang s.d. satu tahun ke depan dari periode aktif) */
    public function startYears(): array
    {
        $y = $this->ctx->year();

        return range($y - 2, $y + 1);
    }

    public function build(?int $startYear = null): array
    {
        $periodYear = $this->ctx->year();
        $start = in_array($startYear, $this->startYears(), true) ? $startYear : $periodYear;
        $years = range($start, $start + 4);

        $objectives = StrategicObjective::with(['theme', 'owner', 'kpis' => fn ($q) => $q->where('is_active', true)->orderBy('code')->with('owner')])
            ->orderByRaw("CASE perspective WHEN 'keuangan' THEN 1 WHEN 'pelanggan' THEN 2 WHEN 'proses' THEN 3 ELSE 4 END")
            ->orderBy('sort')->orderBy('code')->get();

        $kpis = $objectives->flatMap->kpis->values();
        $kpiRows = $kpis->map(fn (Kpi $k) => [
            'kpi' => $k,
            'objective' => $objectives->firstWhere('id', $k->strategic_objective_id),
            'targets' => collect($years)->mapWithKeys(fn ($y) => [$y => $this->projectTarget($k, $y, $periodYear)])->all(),
        ]);

        $initiatives = StrategicInitiative::with(['theme', 'owner', 'objectives', 'budgets'])->orderBy('code')->get();
        $funding = $initiatives->map(function (StrategicInitiative $i) use ($years, $periodYear) {
            $base = (float) ($i->budgets->sum('planned_amount') ?: $i->budget_planned ?: 0);

            return [
                'initiative' => $i,
                'base' => $base,
                'sources' => $i->budgets->pluck('funding_source')->filter()->unique()->implode(', ') ?: '-',
                'years' => collect($years)->mapWithKeys(fn ($y) => [$y => $base > 0 ? round($base * (1 + self::BUDGET_GROWTH) ** ($y - $periodYear), -6) : null])->all(),
            ];
        });

        $themes = StrategicTheme::with(['choices' => fn ($q) => $q->orderBy('code')])->orderBy('sort')->get();
        $swot = SwotItem::orderBy('type')->orderBy('sort')->get()->groupBy('type');
        $tows = TowsStrategy::orderBy('type')->orderByDesc('priority')->get();

        return [
            'company' => $this->ctx->company(),
            'period' => $this->ctx->period(),
            'periodYear' => $periodYear,
            'start' => $start,
            'end' => $start + 4,
            'years' => $years,
            'profile' => AtmanProfile::first(),
            'values' => CoreValue::orderBy('sort')->get(),
            'capabilities' => Capability::orderByDesc('is_core')->orderBy('name')->get(),
            'regulations' => Regulation::orderBy('effective_date')->get(),
            'pestel' => PestelAnalysis::orderBy('factor')->get()->groupBy('factor'),
            'swot' => $swot,
            'swotScore' => $swot->map(fn ($items) => round($items->sum(fn ($s) => (float) $s->weight * (float) $s->rating), 2)),
            'issues' => StrategicIssue::orderByDesc('urgency')->orderByDesc('impact')->get(),
            'stakeholders' => Stakeholder::orderByDesc('influence')->orderByDesc('interest')->get(),
            'tows' => $tows,
            'themes' => $themes,
            'objectives' => $objectives,
            'byPerspective' => $objectives->groupBy('perspective'),
            'relations' => ObjectiveRelation::with(['source', 'target'])->get(),
            'kpiRows' => $kpiRows,
            'initiatives' => $initiatives,
            'funding' => $funding,
            'fundingTotals' => collect($years)->mapWithKeys(fn ($y) => [$y => $funding->sum(fn ($f) => $f['years'][$y] ?? 0)])->all(),
            'perspectives' => Terms::perspectives(),
        ];
    }

    /**
     * Usulan target tahunan: target periode aktif diteruskan dengan laju (target − baseline) per tahun,
     * dibatasi 0–100 untuk satuan persen dan tidak negatif.
     */
    public function projectTarget(Kpi $kpi, int $year, int $periodYear): ?float
    {
        if ($kpi->annual_target === null) {
            return null;
        }
        $target = (float) $kpi->annual_target;
        $step = $kpi->baseline === null ? 0.0 : $target - (float) $kpi->baseline;
        $v = $target + $step * ($year - $periodYear);
        if ($kpi->unit === '%') {
            $v = min(100, $v);
        }

        return round(max(0, $v), (int) ($kpi->decimals ?? 0));
    }
}
