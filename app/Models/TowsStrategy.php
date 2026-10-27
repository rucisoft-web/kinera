<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class TowsStrategy extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'TOWS';

    public function issues(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(StrategicIssue::class, 'tows_strategy_issue');
    }
}
