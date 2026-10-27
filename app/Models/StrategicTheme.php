<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class StrategicTheme extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'TEMA';

    public function objectives(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StrategicObjective::class);
    }

    public function initiatives(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StrategicInitiative::class);
    }

    public function choices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StrategicChoice::class);
    }
}
