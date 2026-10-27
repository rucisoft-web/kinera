<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class Institutionalization extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'next_review_date' => 'date',
        ];
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
