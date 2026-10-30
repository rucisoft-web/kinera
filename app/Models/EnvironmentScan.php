<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class EnvironmentScan extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'SCAN';

    protected function casts(): array
    {
        return [
            'scan_date' => 'date',
        ];
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
