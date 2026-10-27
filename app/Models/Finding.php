<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Finding extends Model
{
    use Auditable, BelongsToDhastramContext;

    public const STATUSES = [
        'terbuka' => 'Terbuka',
        'dalam_tindak_lanjut' => 'Dalam tindak lanjut',
        'siap_ditutup' => 'Siap ditutup',
        'ditutup' => 'Ditutup',
    ];

    public const SEVERITIES = ['rendah' => 'Rendah', 'sedang' => 'Sedang', 'tinggi' => 'Tinggi', 'kritis' => 'Kritis'];

    public const SOURCES = ['tinjauan' => 'Tinjauan kinerja', 'timwas' => 'Dewan Pengawas', 'audit' => 'Audit', 'pelanggan' => 'Ulasan pelanggan', 'internal' => 'Internal'];

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'TMN';

    protected function casts(): array
    {
        return [
            'raised_at' => 'date',
            'closed_at' => 'datetime',
            'amount' => 'float',
        ];
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(PerformanceReview::class, 'performance_review_id');
    }

    public function kpi(): BelongsTo
    {
        return $this->belongsTo(Kpi::class);
    }

    public function initiative(): BelongsTo
    {
        return $this->belongsTo(StrategicInitiative::class, 'strategic_initiative_id');
    }

    public function raiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'raised_by');
    }

    public function closer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function correctiveActions(): HasMany
    {
        return $this->hasMany(CorrectiveAction::class);
    }

    public function allActionsDone(): bool
    {
        $actions = $this->correctiveActions()->get();

        return $actions->isNotEmpty() && $actions->every(fn ($a) => $a->status === 'selesai');
    }

    /** Status mengikuti tindakan korektif, kecuali sudah ditutup. */
    public function syncStatus(): void
    {
        if ($this->status === 'ditutup') {
            return;
        }
        $actions = $this->correctiveActions()->get();
        $status = match (true) {
            $actions->isEmpty() => 'terbuka',
            $actions->every(fn ($a) => $a->status === 'selesai') => 'siap_ditutup',
            default => 'dalam_tindak_lanjut',
        };
        if ($status !== $this->status) {
            $this->forceFill(['status' => $status])->saveQuietly();
        }
    }

    public function getShortCodeAttribute(): ?string
    {
        return \App\Support\Fmt::shortCode($this->code);
    }
}
