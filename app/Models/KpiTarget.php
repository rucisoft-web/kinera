<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class KpiTarget extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    public function kpi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kpi::class);
    }
}
