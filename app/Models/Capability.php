<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_core' => 'boolean',
        ];
    }
}
