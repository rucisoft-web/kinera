<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class MaturityAnswer extends Model
{
    use Auditable;

    protected $guarded = ['id'];

    public function assessment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MaturityAssessment::class, 'maturity_assessment_id');
    }

    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MaturityQuestion::class, 'maturity_question_id');
    }
}
