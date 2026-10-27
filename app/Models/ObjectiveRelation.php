<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class ObjectiveRelation extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    public function source(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StrategicObjective::class, 'source_objective_id');
    }

    public function target(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StrategicObjective::class, 'target_objective_id');
    }
}
