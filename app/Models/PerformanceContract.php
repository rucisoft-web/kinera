<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToDhastramContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Kontrak Kinerja Pemimpin BLU (PER-6/PB/2025). Versi 0 = kontrak awal, versi berikutnya = perubahan (Pasal 19)
 * atau adendum peralihan (Pasal 21). Kolom indicators menyimpan potret Indikator Kinerja beserta target trajektori
 * pada saat kontrak disusun, sehingga perubahan target di kemudian hari tidak mengubah kontrak yang sudah ditandatangani.
 */
class PerformanceContract extends Model
{
    use Auditable, BelongsToDhastramContext;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'KK';

    public const KINDS = ['awal' => 'Kontrak Kinerja awal', 'perubahan' => 'Perubahan Kontrak Kinerja', 'adendum' => 'Adendum (peralihan)'];

    public const STATUSES = ['draf' => 'Draf', 'diusulkan' => 'Diusulkan ke DJPb', 'disepakati' => 'Disepakati (BA pembahasan)', 'ditandatangani' => 'Ditandatangani (berlaku)', 'digantikan' => 'Digantikan versi baru'];

    public const STATUS_COLORS = ['draf' => 'gray', 'diusulkan' => 'warning', 'disepakati' => 'info', 'ditandatangani' => 'success', 'digantikan' => 'gray'];

    /** Dasar perubahan Kontrak Kinerja: Pasal 19 ayat (1); adendum peralihan: Pasal 21. */
    public const BASES = [
        'penugasan' => 'Pasal 19 (1) a — melaksanakan kebijakan/penugasan pemerintah',
        'kejadian_luar_biasa' => 'Pasal 19 (1) b — kejadian luar biasa yang berdampak pada layanan',
        'perubahan_kebijakan' => 'Pasal 19 (1) c — perubahan kebijakan Pembina Teknis/K/L (mandat layanan)',
        'peralihan' => 'Pasal 21 — penyesuaian Kontrak Kinerja 2025 dengan PER-6/PB/2025',
    ];

    /** Perubahan yang sudah ditandatangani menggantikan versi sebelumnya (Pasal 19 ayat 7). */
    protected static function booted(): void
    {
        static::saved(function (self $c) {
            if ($c->status === 'ditandatangani' && $c->wasChanged('status') || $c->status === 'ditandatangani' && $c->wasRecentlyCreated) {
                static::withoutGlobalScopes()->where('analysis_period_id', $c->analysis_period_id)->where('version', '<', $c->version)
                    ->where('status', 'ditandatangani')->update(['status' => 'digantikan']);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'indicators' => 'array',
            'proposed_at' => 'date',
            'discussed_at' => 'date',
            'leader_signed_at' => 'date',
            'dg_signed_at' => 'date',
            'legal_case_free' => 'boolean',
            'other_requirements_met' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getLabelAttribute(): string
    {
        return ($this->version === 0 ? 'Kontrak Kinerja' : (self::KINDS[$this->kind] ?? 'Perubahan').' ke-'.$this->version).' '.($this->analysisPeriod?->year ?? '');
    }

    public function isSigned(): bool
    {
        return $this->status === 'ditandatangani';
    }

    /** Indikator yang ditandai berubah (target turun) pada versi ini: capaiannya dihitung paling tinggi 100% (Pasal 19 ayat 8). */
    public function loweredCodes(): array
    {
        return collect($this->indicators ?? [])->filter(fn ($i) => ($i['change'] ?? null) === 'target_turun')->pluck('code')->all();
    }
}
