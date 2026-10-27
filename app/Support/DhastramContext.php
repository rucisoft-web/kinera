<?php

namespace App\Support;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\UserContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * Menyimpan konteks perusahaan & perioda aktif di sesi dan tabel user_contexts.
 * Ketika tidak ada pengguna (console/seeder) konteks bisa di-set manual lewat set().
 */
class DhastramContext
{
    protected ?int $companyId = null;

    protected ?int $periodId = null;

    protected bool $resolved = false;

    protected bool $disabled = false;

    public function set(?int $companyId, ?int $periodId): void
    {
        $this->companyId = $companyId;
        $this->periodId = $periodId;
        $this->resolved = true;
    }

    public function switch(int $companyId, int $periodId): void
    {
        $this->set($companyId, $periodId);
        if (app()->bound('session') && session()->isStarted()) {
            session(['dhastram.company_id' => $companyId, 'dhastram.period_id' => $periodId]);
        }
        // Akun Tamu dipakai bersama banyak pengunjung: konteks cukup di sesi masing-masing
        if (($user = Auth::user()) && ! $user->isGuestVisitor()) {
            UserContext::withoutGlobalScopes()->updateOrCreate(
                ['user_id' => $user->id],
                ['company_id' => $companyId, 'analysis_period_id' => $periodId],
            );
        }
    }

    public function clear(): void
    {
        $this->companyId = $this->periodId = null;
        $this->resolved = false;
    }

    /** Jalankan callback tanpa penyaringan konteks. */
    public function withoutScope(callable $callback): mixed
    {
        $prev = $this->disabled;
        $this->disabled = true;
        try {
            return $callback();
        } finally {
            $this->disabled = $prev;
        }
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    protected function resolve(): void
    {
        if ($this->resolved) {
            return;
        }
        $this->resolved = true;
        $user = Auth::user();
        if (! $user) {
            return;
        }
        $companyId = session('dhastram.company_id');
        $periodId = session('dhastram.period_id');
        if ((! $companyId || ! $periodId) && ! $user->isGuestVisitor()) {
            $ctx = UserContext::where('user_id', $user->id)->first();
            $companyId ??= $ctx?->company_id;
            $periodId ??= $ctx?->analysis_period_id;
        }
        $companyIds = $user->accessibleCompanyIds();
        if (! $companyId || ! in_array((int) $companyId, $companyIds, true)) {
            $companyId = $companyIds[0] ?? null;
            $periodId = null;
        }
        if ($companyId && (! $periodId || ! AnalysisPeriod::where('company_id', $companyId)->whereKey($periodId)->exists())) {
            $periodId = AnalysisPeriod::where('company_id', $companyId)->orderByDesc('is_active')->orderBy('year')->value('id');
        }
        $this->companyId = $companyId ? (int) $companyId : null;
        $this->periodId = $periodId ? (int) $periodId : null;
    }

    public function companyId(): ?int
    {
        $this->resolve();

        return $this->companyId;
    }

    public function periodId(): ?int
    {
        $this->resolve();

        return $this->periodId;
    }

    public function company(): ?Company
    {
        return $this->companyId() ? Company::find($this->companyId()) : null;
    }

    public function period(): ?AnalysisPeriod
    {
        return $this->periodId() ? AnalysisPeriod::find($this->periodId()) : null;
    }

    public function year(): int
    {
        return $this->period()?->year ?? (int) now()->year;
    }

    /** Bulan terakhir yang seharusnya sudah ada realisasinya (bulan lalu, dibatasi tahun perioda). */
    public function lastClosedMonth(): int
    {
        $year = $this->year();
        $now = now();
        if ($now->year > $year) {
            return 12;
        }
        if ($now->year < $year) {
            return 0;
        }

        return max(0, $now->month - 1);
    }
}
