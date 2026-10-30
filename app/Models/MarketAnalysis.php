<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class MarketAnalysis extends Model
{
    use BelongsToBscContext, Auditable;

    protected $table = 'market_analyses';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_target' => 'boolean',
        ];
    }
}
