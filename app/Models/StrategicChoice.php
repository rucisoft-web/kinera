<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class StrategicChoice extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'PIL';

    public function theme(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StrategicTheme::class, 'strategic_theme_id');
    }
}
