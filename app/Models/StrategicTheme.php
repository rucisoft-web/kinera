<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class StrategicTheme extends Model
{
    use BelongsToBscContext, Auditable;

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
