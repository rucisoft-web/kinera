<?php

namespace App\Filament\Concerns;

use App\Models;
use App\Support\DhastramContext;
use Illuminate\Support\Facades\Auth;

/**
 * Pembatasan multi-perusahaan untuk menu Administrasi.
 * Super Admin mengelola semua perusahaan; admin lain hanya perusahaan yang sedang aktif.
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
     * Admin perusahaan hanya boleh mengubah akun yang terdaftar di perusahaan aktif saja.
     * Akun yang juga terdaftar di perusahaan lain (atau berperan global) hanya diubah Super Admin,
     * supaya reset sandi tidak bisa dipakai untuk masuk ke perusahaan lain.
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

    /** Pilihan perusahaan pada formulir: semua (Super Admin) atau hanya perusahaan aktif. */
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
