<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date',
            'due_date' => 'date',
        ];
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
