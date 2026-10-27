<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use App\Services\KpiScoringService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiActual extends Model
{
    use Auditable, BelongsToDhastramContext;

    public const STATUSES = [
        'draf' => 'Draf',
        'terverifikasi' => 'Terverifikasi',
        'disetujui' => 'Disetujui',
        'dikembalikan' => 'Dikembalikan',
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'actual' => 'float',
            'is_projection' => 'boolean',
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function kpi(): BelongsTo
    {
        return $this->belongsTo(Kpi::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isLocked(): bool
    {
        return $this->status === 'disetujui';
    }

    public function getTargetAttribute(): ?float
    {
        return $this->kpi?->targetFor((int) $this->month);
    }

    public function getScoreAttribute(): ?float
    {
        return app(KpiScoringService::class)->score($this->kpi, $this->actual, $this->target);
    }

    public function getScoreStatusAttribute(): ?string
    {
        return app(KpiScoringService::class)->status($this->score);
    }

    public function auditLabel(): string
    {
        return ($this->kpi?->short_code ?? 'IK').' bulan '.$this->month;
    }
}
