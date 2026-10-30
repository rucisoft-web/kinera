<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class KpiTarget extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    public function kpi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kpi::class);
    }
}
