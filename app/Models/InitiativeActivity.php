<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class InitiativeActivity extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
            'completed_at' => 'date',
            'progress_updated_at' => 'datetime',
        ];
    }

    public function initiative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StrategicInitiative::class, 'strategic_initiative_id');
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    protected static function booted(): void
    {
        static::saving(function (self $a) {
            if ((float) $a->progress >= 100) {
                $a->status = 'selesai';
                $a->completed_at ??= now()->toDateString();
            } elseif ($a->due_date && $a->due_date->isPast() && (float) $a->progress < 100) {
                $a->status = 'terlambat';
            } elseif ((float) $a->progress > 0) {
                $a->status = 'berjalan';
            } else {
                $a->status = $a->status === 'terlambat' ? 'belum' : ($a->status ?: 'belum');
            }
            if ($a->isDirty('progress')) {
                $a->progress_updated_at = now();
            }
        });
        static::saved(fn (self $a) => $a->initiative?->recalculateProgress());
        static::deleted(fn (self $a) => $a->initiative?->recalculateProgress());
    }
}
