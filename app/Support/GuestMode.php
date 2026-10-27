<?php

namespace App\Support;

use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Models\UserContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Akses Tamu untuk situs demo/promosi: pengunjung masuk dengan satu klik dan hanya bisa melihat.
 * Aktif bila RUCI_GUEST_LOGIN=true. Peran `tamu` hanya berisi izin lihat; selain itu setiap
 * penyimpanan/penghapusan model oleh Tamu ditolak di sini sebagai pengaman terakhir.
 */
class GuestMode
{
    public const ROLE = 'tamu';

    public const MESSAGE = 'Mode demo: akun Tamu hanya dapat melihat, data tidak dapat diubah.';

    /** Kolom User yang boleh berubah saat Tamu masuk/keluar. */
    protected const USER_HOUSEKEEPING = ['remember_token', 'last_login_at', 'updated_at'];

    public static function enabled(): bool
    {
        return (bool) config('dhastram.guest_login');
    }

    public static function email(): string
    {
        return (string) config('dhastram.guest_email');
    }

    public static function isGuest(?User $user = null): bool
    {
        $user ??= Auth::user();

        return $user instanceof User && $user->isGuestVisitor();
    }

    /** Akun Tamu bersama: dibuat bila belum ada, peran `tamu` global (semua perusahaan). */
    public static function user(): User
    {
        $role = Role::where('slug', self::ROLE)->first();
        if (! $role) {
            (new \Database\Seeders\DhastramRolePermissionSeeder)->run();
            $role = Role::where('slug', self::ROLE)->firstOrFail();
        }
        $user = User::where('email', self::email())->first() ?? User::forceCreate([
            'name' => 'Tamu',
            'email' => self::email(),
            'password' => Str::password(32),
            'job_title' => 'Pengunjung demo',
            'is_active' => true,
            'must_change_password' => false,
        ]);
        if (! $user->is_active || $user->must_change_password) {
            $user->forceFill(['is_active' => true, 'must_change_password' => false])->saveQuietly();
        }
        if (! $user->roles()->where('roles.id', $role->id)->exists()) {
            $user->roles()->attach($role->id, ['company_id' => null]);
            $user->flushPermissionCache();
        }

        return $user;
    }

    /** Perusahaan pembuka untuk Tamu: contoh pertama (DFI bila ada). */
    public static function startCompany(): ?Company
    {
        return Company::where('code', 'DFI')->first()
            ?? Company::where('is_demo', true)->orderBy('id')->first()
            ?? Company::orderBy('id')->first();
    }

    public static function registerWriteGuard(): void
    {
        Event::listen(['eloquent.saving: *', 'eloquent.deleting: *'], function (string $event, array $payload) {
            $model = $payload[0] ?? null;
            if (! $model instanceof Model || ! Auth::hasUser() || ! self::isGuest()) {
                return null;
            }
            // AuditLog hanya tercatat setelah penyimpanan yang memang diizinkan di bawah
            if ($model instanceof UserContext || $model instanceof DatabaseNotification || $model instanceof AuditLog) {
                return null;
            }
            if ($model instanceof User && $model->is(Auth::user()) && str_starts_with($event, 'eloquent.saving')
                && array_diff(array_keys($model->getDirty()), self::USER_HOUSEKEEPING) === []) {
                return null;
            }

            throw new HttpException(403, self::MESSAGE);
        });
    }
}
