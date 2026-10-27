<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class StrategicObjective extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'OBJ';

    public function theme(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StrategicTheme::class, 'strategic_theme_id');
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function kpis(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Kpi::class);
    }

    public function outgoingRelations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ObjectiveRelation::class, 'source_objective_id');
    }

    public function incomingRelations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ObjectiveRelation::class, 'target_objective_id');
    }

    public function initiatives(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(StrategicInitiative::class, 'initiative_objective');
    }

    public function getLabelAttribute(): string
    {
        return trim(($this->short_code ? $this->short_code.' · ' : '').$this->title);
    }

    public function getShortCodeAttribute(): ?string
    {
        return \App\Support\Fmt::shortCode($this->code);
    }
}
