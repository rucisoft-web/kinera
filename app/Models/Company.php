<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use Auditable;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_demo' => 'boolean',
        ];
    }

    public function periods(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AnalysisPeriod::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user');
    }
}
