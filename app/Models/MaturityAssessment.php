<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class MaturityAssessment extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'MAT';

    protected function casts(): array
    {
        return [
            'assessment_date' => 'date',
        ];
    }

    public function assessor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MaturityAnswer::class);
    }
}
