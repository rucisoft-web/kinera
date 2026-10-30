<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class SwotItem extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    public const TYPES = ['S' => 'Kekuatan (S)', 'W' => 'Kelemahan (W)', 'O' => 'Peluang (O)', 'T' => 'Ancaman (T)'];
}
