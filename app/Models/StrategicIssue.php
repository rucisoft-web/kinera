<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class StrategicIssue extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'ISU';

    public function stakeholders(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Stakeholder::class, 'strategic_issue_stakeholder');
    }

    public function towsStrategies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TowsStrategy::class, 'tows_strategy_issue');
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getPriorityScoreAttribute(): int
    {
        return (int) $this->urgency * (int) $this->impact;
    }
}
