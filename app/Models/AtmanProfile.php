<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class AtmanProfile extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function approver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /** Nilai inti pada perioda yang sama (dipakai relation manager). */
    public function coreValues(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CoreValue::class, 'analysis_period_id', 'analysis_period_id')->orderBy('sort');
    }
}
