<?php

namespace App\Services;

use App\Models\MaturityAssessment;
use App\Support\Terms;

/** Skor kematangan manajemen strategi dari jawaban kuesioner (skala 1-5). */
class MaturityScoringService
{
    public const LEVELS = [1 => 'Awal', 2 => 'Berkembang', 3 => 'Terdefinisi', 4 => 'Terkelola', 5 => 'Optimal'];

    public function byPhase(MaturityAssessment $a): array
    {
        $a->loadMissing('answers.question');
        $out = [];
        foreach (Terms::phases() as $key => $label) {
            $answers = $a->answers->filter(fn ($x) => $x->question?->phase === $key);
            $w = $answers->sum(fn ($x) => (float) $x->question->weight ?: 1);
            $out[$key] = [
                'label' => $label,
                'score' => $w > 0 ? round($answers->sum(fn ($x) => $x->score * ((float) $x->question->weight ?: 1)) / $w, 2) : null,
                'answered' => $answers->count(),
            ];
        }

        return $out;
    }

    public function recalculate(MaturityAssessment $a): void
    {
        $phases = collect($this->byPhase($a))->pluck('score')->filter(fn ($s) => $s !== null);
        $overall = $phases->isNotEmpty() ? round($phases->avg(), 2) : null;
        $a->forceFill([
            'overall_score' => $overall,
            'level' => $overall ? max(1, min(5, (int) floor($overall + 0.0001))) : null,
        ])->saveQuietly();
    }

    public function levelName(?int $level): string
    {
        return $level ? (self::LEVELS[$level] ?? '-') : '-';
    }
}
