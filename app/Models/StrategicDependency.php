<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class StrategicDependency extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    public function initiative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StrategicInitiative::class, 'strategic_initiative_id');
    }

    public function objective(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StrategicObjective::class, 'strategic_objective_id');
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
