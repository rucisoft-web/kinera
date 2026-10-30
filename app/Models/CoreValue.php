<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class CoreValue extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];
}
