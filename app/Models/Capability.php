<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_core' => 'boolean',
        ];
    }
}
