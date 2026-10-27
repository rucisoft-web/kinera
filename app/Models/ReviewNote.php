<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class ReviewNote extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    public function review(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PerformanceReview::class, 'performance_review_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
