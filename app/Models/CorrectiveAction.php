<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class CorrectiveAction extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'date',
        ];
    }

    public function finding(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Finding::class);
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    protected static function booted(): void
    {
        static::saving(function (self $c) {
            if ($c->status === 'selesai' || (int) $c->progress >= 100) {
                $c->status = 'selesai';
                $c->progress = 100;
                $c->completed_at ??= now()->toDateString();
            } elseif ((int) $c->progress > 0 && $c->status === 'belum') {
                $c->status = 'berjalan';
            }
        });
        static::saved(fn (self $c) => $c->finding?->syncStatus());
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'selesai' && $this->due_date && $this->due_date->lt(today());
    }
}
