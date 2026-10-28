<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

/** Draf Rencana Kerja tahunan yang disusun dari isian Kinera (dengan Claude atau mode contoh). */
class WorkPlanDraft extends Model
{
    use BelongsToBscContext;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['attachments' => 'array', 'content' => 'array'];
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
