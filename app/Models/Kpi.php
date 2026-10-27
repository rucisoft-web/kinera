<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kpi extends Model
{
    use Auditable, BelongsToBscContext;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'IK';

    public const UNITS = ['Rp miliar' => 'Rp miliar', 'Rp juta' => 'Rp juta', 'Rp ribu' => 'Rp ribu', 'US$ juta' => 'US$ juta', 'US$/boe' => 'US$/boe', '%' => '%', 'indeks' => 'Indeks', 'skor' => 'Skor', 'kasus' => 'Kasus', 'event' => 'Event', 'unit' => 'Unit', 'hari' => 'Hari', 'jam' => 'Jam', 'menit' => 'Menit', 'rasio' => 'Rasio', 'kali' => 'Kali (x)', 'orang' => 'Orang', 'ribu' => 'Ribu', 'juta' => 'Juta', 'ribu ton' => 'Ribu ton', 'ribu unit' => 'Ribu unit', 'mboepd' => 'mboepd', 'mmboe' => 'mmboe', 'GWh' => 'GWh', 'MWh' => 'MWh', 'kg CO₂e/ton' => 'kg CO₂e/ton', 'MW' => 'MW', 'tahun' => 'Tahun', 'm²' => 'm²', 'km' => 'km', 'ha' => 'ha'];

    public const FREQUENCIES = ['bulanan' => 'Bulanan', 'mingguan' => 'Mingguan', 'kuartalan' => 'Triwulanan', 'semesteran' => 'Semesteran', 'tahunan' => 'Tahunan'];

    /** Aspek Indikator Kinerja Kontrak Kinerja: paling sedikit layanan & keuangan (PER-6/PB/2025 Pasal 5 ayat 6–7). */
    public const ASPECTS = ['layanan' => 'Aspek layanan', 'keuangan' => 'Aspek keuangan', 'penugasan' => 'Kebijakan/penugasan K/L atau Menkeu'];

    /** Pihak yang dilibatkan menyusun Definisi Operasional (Pasal 5 ayat 9). */
    public const DO_PARTNERS = ['layanan' => 'Pembina Teknis', 'keuangan' => 'Direktorat Jenderal Perbendaharaan', 'penugasan' => 'Pembina Teknis / pemberi penugasan'];

    /** Indikator penanda syarat insentif (Pasal 14 ayat 4 huruf d–f). */
    public const INCENTIVE_ROLES = ['pnbp' => 'Target PNBP (huruf d)', 'ebitda' => 'Surplus EBITDA (huruf e)', 'opini' => 'Opini laporan keuangan audited (huruf f)'];

    public const AGGREGATIONS = ['sum' => 'Jumlah (akumulasi)', 'average' => 'Rata-rata', 'last' => 'Nilai terakhir'];

    /** Bulan pelaporan sesuai frekuensi: kuartalan Mar/Jun/Sep/Des, semesteran Jun/Des, tahunan Des. */
    public function isDueIn(int $month): bool
    {
        return match ($this->frequency) {
            'kuartalan' => $month % 3 === 0,
            'semesteran' => $month % 6 === 0,
            'tahunan' => $month === 12,
            default => true,
        };
    }

    protected function casts(): array
    {
        return [
            'is_financial' => 'boolean',
            'is_critical' => 'boolean',
            'is_mandatory' => 'boolean',
            'max_achievement' => 'float',
            'is_active' => 'boolean',
            'baseline' => 'float',
            'annual_target' => 'float',
            'weight' => 'float',
        ];
    }

    public function objective(): BelongsTo
    {
        return $this->belongsTo(StrategicObjective::class, 'strategic_objective_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function targets(): HasMany
    {
        return $this->hasMany(KpiTarget::class)->orderBy('month');
    }

    public function actuals(): HasMany
    {
        return $this->hasMany(KpiActual::class)->orderBy('month');
    }

    public function getLabelAttribute(): string
    {
        return ($this->short_code ? $this->short_code.' · ' : '').$this->name;
    }

    public function getShortCodeAttribute(): ?string
    {
        return \App\Support\Fmt::shortCode($this->code);
    }

    public function targetFor(int $month): ?float
    {
        $t = $this->relationLoaded('targets') ? $this->targets->firstWhere('month', $month) : $this->targets()->where('month', $month)->first();

        return $t ? (float) $t->target : null;
    }

    /** Capaian maksimum yang diakui (KMK remunerasi BLU); bila kosong memakai batas umum aplikasi. */
    public function achievementCap(): float
    {
        return (float) ($this->max_achievement ?: config('bsc.score_cap', 120));
    }

    public function getAspectLabelAttribute(): string
    {
        return self::ASPECTS[$this->aspect] ?? (string) $this->aspect;
    }

    public function isOwnedBy(?User $user): bool
    {
        return $user && (int) $this->owner_id === (int) $user->id;
    }
}
