<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;

class PestelAnalysis extends Model
{
    use BelongsToDhastramContext, Auditable;

    protected $table = 'pestel_analyses';

    protected $guarded = ['id'];

    public const FACTORS = ['politik' => 'Politik', 'ekonomi' => 'Ekonomi', 'sosial' => 'Sosial', 'teknologi' => 'Teknologi', 'lingkungan' => 'Lingkungan', 'hukum' => 'Hukum'];

    public function getScoreAttribute(): int
    {
        return (int) $this->impact_level * (int) $this->likelihood;
    }
}
