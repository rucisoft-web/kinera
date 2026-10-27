<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use BelongsToBscContext, Auditable;

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'REV';

    protected function casts(): array
    {
        return [
            'review_date' => 'date',
        ];
    }

    public function recorder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReviewNote::class)->latest();
    }

    public function findings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Finding::class);
    }

    public const TYPES = [
        'weekly_gm' => 'Laporan Mingguan Pimpinan',
        'rapat_manajemen' => 'Rapat Manajemen',
        'rapat_timwas' => 'Rapat Dewan Pengawas',
        'kuartalan' => 'Tinjauan Kuartalan',
        'tahunan' => 'Tinjauan Tahunan',
    ];
}
