<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class Stakeholder extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    public function issues(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(StrategicIssue::class, 'strategic_issue_stakeholder');
    }

    public function getQuadrantAttribute(): string
    {
        $hi = $this->influence >= 3;
        $ii = $this->interest >= 3;

        return match (true) {
            $hi && $ii => 'Kelola dekat',
            $hi && ! $ii => 'Jaga kepuasan',
            ! $hi && $ii => 'Jaga informasi',
            default => 'Pantau',
        };
    }
}
