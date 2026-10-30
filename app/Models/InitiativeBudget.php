<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class InitiativeBudget extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'realization_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function initiative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StrategicInitiative::class, 'strategic_initiative_id');
    }

    public function verifier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
