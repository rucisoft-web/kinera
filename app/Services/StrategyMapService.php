<?php

namespace App\Services;

use App\Models\ObjectiveRelation;
use App\Support\Terms;

/** Menyusun peta strategi: 4 lajur perspektif, kartu sasaran, dan panah sebab-akibat. */
class StrategyMapService
{
    public function __construct(protected StrategicScoreService $score) {}

    public function build(): array
    {
        $objectives = $this->score->objectives();
        $lanes = [];
        $order = ['keuangan', 'pelanggan', 'proses', 'sdm'];
        $laneHeight = 170;
        $cardW = 210;
        $cardH = 92;
        // lebar kanvas ikut baris terpadat supaya kartu tidak saling menimpa (viewBox SVG menyesuaikan layar)
        $busiest = $objectives->countBy(fn ($o) => $o['objective']->perspective)->max() ?? 0;
        $width = max(1100, 150 + $busiest * ($cardW + 24));
        $positions = [];
        foreach ($order as $li => $key) {
            $rows = $objectives->filter(fn ($o) => $o['objective']->perspective === $key)->values();
            $n = max(1, $rows->count());
            $gap = ($width - 150) / $n;
            $cards = [];
            foreach ($rows as $i => $r) {
                $x = 150 + $gap * $i + ($gap - $cardW) / 2;
                $y = $li * $laneHeight + ($laneHeight - $cardH) / 2;
                $positions[$r['objective']->id] = ['x' => $x, 'y' => $y];
                $cards[] = $r + ['x' => round($x), 'y' => round($y)];
            }
            $lanes[] = ['key' => $key, 'label' => Terms::get($key), 'color' => Terms::perspectiveColor($key), 'y' => $li * $laneHeight, 'cards' => $cards];
        }
        $arrows = ObjectiveRelation::get()->filter(fn ($r) => isset($positions[$r->source_objective_id], $positions[$r->target_objective_id]))
            ->map(function ($r) use ($positions, $cardW, $cardH) {
                $s = $positions[$r->source_objective_id];
                $t = $positions[$r->target_objective_id];
                $sx = $s['x'] + $cardW / 2;
                $tx = $t['x'] + $cardW / 2;
                $up = $t['y'] < $s['y'];
                $sy = $up ? $s['y'] : $s['y'] + $cardH;
                $ty = $up ? $t['y'] + $cardH : $t['y'];
                if (abs($t['y'] - $s['y']) < 1) { // satu lajur
                    $sy = $ty = $s['y'] + $cardH / 2;
                    $sx = $s['x'] + ($tx > $sx ? $cardW : 0);
                    $tx = $t['x'] + ($tx > $sx ? 0 : $cardW);
                }

                return ['sx' => round($sx), 'sy' => round($sy), 'tx' => round($tx), 'ty' => round($ty), 'strength' => $r->strength];
            })->values();

        return ['lanes' => $lanes, 'arrows' => $arrows, 'width' => $width, 'height' => $laneHeight * 4, 'cardW' => $cardW, 'cardH' => $cardH];
    }
}
