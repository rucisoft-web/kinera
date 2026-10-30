<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class Institutionalization extends Model
{
    use BelongsToBscContext, Auditable;

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
