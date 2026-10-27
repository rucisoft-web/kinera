<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class CoreValue extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];
}
