<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Riwayat upload workbook Excel per perusahaan/perioda (lihat App\Services\Import\BscImporter). */
class DataImport extends Model
{
    public const STATUSES = ['selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'summary' => 'array',
            'issues' => 'array',
            'rolled_back_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function analysisPeriod(): BelongsTo
    {
        return $this->belongsTo(AnalysisPeriod::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function changes(): HasMany
    {
        return $this->hasMany(DataImportChange::class);
    }

    /** Hanya import terakhir yang masih berlaku pada perusahaan/perioda yang sama yang boleh dibatalkan. */
    public function canRollback(): bool
    {
        if ($this->rolled_back_at) {
            return false;
        }

        return ! static::where('company_id', $this->company_id)->where('analysis_period_id', $this->analysis_period_id)
            ->whereNull('rolled_back_at')->where('id', '>', $this->id)->exists();
    }
}
