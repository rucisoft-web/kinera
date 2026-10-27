<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use SensitiveParameter;

class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasName
{
    use Auditable, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = ['password', 'remember_token', 'app_authentication_secret', 'app_authentication_recovery_codes'];

    /** @var array<string,array<int,string>> cache izin per perusahaan */
    protected array $permissionCache = [];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'password_changed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'app_authentication_secret' => 'encrypted',
            'app_authentication_recovery_codes' => 'encrypted:array',
        ];
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')->withPivot('company_id');
    }

    public function roleAssignments(): HasMany
    {
        return $this->hasMany(RoleAssignment::class);
    }

    public function ownedKpis(): HasMany
    {
        return $this->hasMany(Kpi::class, 'owner_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active;
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    /** Peran yang berlaku untuk perusahaan tertentu (global + khusus perusahaan). */
    public function rolesFor(?int $companyId)
    {
        return $this->roles->filter(fn ($r) => $r->pivot->company_id === null || (int) $r->pivot->company_id === (int) $companyId);
    }

    /** @return array<int,string> */
    public function permissionSlugs(?int $companyId = null): array
    {
        $companyId ??= app(\App\Support\DhastramContext::class)->companyId();
        $key = (string) $companyId;
        if (! isset($this->permissionCache[$key])) {
            $this->loadMissing('roles.permissions');
            $roles = $this->rolesFor($companyId);
            if ($roles->contains('slug', 'super-admin')) {
                $this->permissionCache[$key] = Permission::pluck('slug')->all();
            } else {
                $this->permissionCache[$key] = $roles->flatMap(fn ($r) => $r->permissions->pluck('slug'))->unique()->values()->all();
            }
        }

        return $this->permissionCache[$key];
    }

    public function hasPermission(string $slug, ?int $companyId = null): bool
    {
        return $this->isSuperAdmin() || in_array($slug, $this->permissionSlugs($companyId), true);
    }

    public function hasAnyPermission(array $slugs): bool
    {
        foreach ($slugs as $s) {
            if ($this->hasPermission($s)) {
                return true;
            }
        }

        return false;
    }

    public function hasRole(string $slug): bool
    {
        $this->loadMissing('roles');

        return $this->roles->contains('slug', $slug);
    }

    /** Akun Tamu situs demo: hanya melihat (lihat App\Support\GuestMode). */
    public function isGuestVisitor(): bool
    {
        return $this->hasRole(\App\Support\GuestMode::ROLE);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /** Pengelola (Pimpinan, Koordinator, Super Admin) bebas dari pembatasan "miliknya". */
    public function managesAllData(): bool
    {
        return $this->isSuperAdmin() || in_array('data.manage_all', $this->permissionSlugs(), true);
    }

    /** @return array<int,int> */
    public function accessibleCompanyIds(): array
    {
        if ($this->isSuperAdmin()) {
            return Company::where('is_active', true)->orderBy('id')->pluck('id')->map(fn ($i) => (int) $i)->all();
        }
        $ids = $this->companies()->pluck('companies.id')
            ->merge($this->roles->pluck('pivot.company_id')->filter())
            ->map(fn ($i) => (int) $i)->unique()->sort()->values()->all();
        if ($ids === [] && $this->roles->whereNull('pivot.company_id')->isNotEmpty()) {
            return Company::orderBy('id')->pluck('id')->map(fn ($i) => (int) $i)->all();
        }

        return $ids;
    }

    public function flushPermissionCache(): void
    {
        $this->permissionCache = [];
        $this->unsetRelation('roles');
    }

    // ---- Filament MFA (aplikasi autentikator)
    public function getAppAuthenticationSecret(): ?string
    {
        return $this->app_authentication_secret;
    }

    public function saveAppAuthenticationSecret(#[SensitiveParameter] ?string $secret): void
    {
        $this->app_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        return $this->email;
    }

    public function getAppAuthenticationRecoveryCodes(): ?array
    {
        return $this->app_authentication_recovery_codes;
    }

    public function saveAppAuthenticationRecoveryCodes(#[SensitiveParameter] ?array $codes): void
    {
        $this->app_authentication_recovery_codes = $codes;
        $this->save();
    }

    public function getRoleNamesAttribute(): string
    {
        return $this->roles->pluck('name')->join(', ');
    }
}
