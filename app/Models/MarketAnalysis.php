<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class MarketAnalysis extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $table = 'market_analyses';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_target' => 'boolean',
        ];
    }
}
