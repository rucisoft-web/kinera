<?php

namespace App\Filament\Concerns;

use App\Models;
use App\Support\DhastramContext;
use Illuminate\Support\Facades\Auth;

/**
 * Pembatasan multi-satker BLU untuk menu Administrasi.
 * Super Admin mengelola semua satker BLU; admin lain hanya satker BLU yang sedang aktif.
 */
trait ScopesToActiveCompany
{
    protected static function managesAllCompanies(): bool
    {
        return (bool) Auth::user()?->isSuperAdmin();
    }

    protected static function activeCompanyId(): ?int
    {
        return app(DhastramContext::class)->companyId();
    }

    /**
     * Admin satker BLU hanya boleh mengubah akun yang terdaftar di satker BLU aktif saja.
     * Akun yang juga terdaftar di satker BLU lain (atau berperan global) hanya diubah Super Admin,
     * supaya reset sandi tidak bisa dipakai untuk masuk ke satker BLU lain.
     */
    protected static function ownsUserAccount(Models\User $user): bool
    {
        if (static::managesAllCompanies()) {
            return true;
        }
        $active = static::activeCompanyId();
        $assignments = $user->roleAssignments()->get(['company_id']);
        if ($user->isSuperAdmin() || $assignments->contains(fn ($a) => $a->company_id === null)) {
            return false;
        }
        $companies = $user->companies()->pluck('companies.id')->merge($assignments->pluck('company_id'))->map(fn ($i) => (int) $i)->unique();

        return $companies->isNotEmpty() && $companies->every(fn ($id) => $id === $active);
    }

    /** Pilihan satker BLU pada formulir: semua (Super Admin) atau hanya satker BLU aktif. */
    protected static function companyOptions(): array
    {
        $q = Models\Company::orderBy('name');
        if (! static::managesAllCompanies()) {
            $q->whereKey(static::activeCompanyId());
        }

        return $q->pluck('name', 'id')->all();
    }

    /** Peran yang boleh diberikan: Super Admin hanya bisa diberikan oleh Super Admin. */
    protected static function assignableRoleOptions(): array
    {
        return Models\Role::orderBy('name')->get()
            ->filter(fn ($r) => $r->slug !== 'super-admin' || static::managesAllCompanies())
            ->pluck('name', 'id')->all();
    }
}
