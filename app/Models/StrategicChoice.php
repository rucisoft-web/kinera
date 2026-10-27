<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class StrategicChoice extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'PIL';

    public function theme(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StrategicTheme::class, 'strategic_theme_id');
    }
}
