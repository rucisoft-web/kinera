<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class InitiativeMilestone extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'achieved_at' => 'date',
            'is_achieved' => 'boolean',
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
        static::saving(function (self $m) {
            if ($m->is_achieved && ! $m->achieved_at) {
                $m->achieved_at = now()->toDateString();
            }
            if (! $m->is_achieved) {
                $m->achieved_at = null;
            }
        });
        static::saved(fn (self $m) => $m->initiative?->recalculateProgress());
        static::deleted(fn (self $m) => $m->initiative?->recalculateProgress());
    }
}
