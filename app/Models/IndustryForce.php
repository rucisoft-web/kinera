<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class IndustryForce extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    public const FORCES = [
        'pendatang_baru' => 'Ancaman Pendatang Baru',
        'kekuatan_pemasok' => 'Daya Tawar Pemasok',
        'kekuatan_pembeli' => 'Daya Tawar Pembeli',
        'produk_pengganti' => 'Ancaman Produk Pengganti',
        'persaingan' => 'Persaingan Antar Pesaing',
    ];
}
