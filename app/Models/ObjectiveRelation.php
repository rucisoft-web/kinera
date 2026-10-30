<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class ObjectiveRelation extends Model
{
    use BelongsToBscContext, Auditable;

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
