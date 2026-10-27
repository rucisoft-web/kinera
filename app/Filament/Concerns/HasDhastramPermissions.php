<?php

namespace App\Filament\Concerns;

use App\Support\Terms;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Hak akses resource berbasis izin (slug).
 * Resource menetapkan: $viewPermission, $editPermission, $navigationGroupKey (kunci Terms atau label).
 */
trait HasDhastramPermissions
{
    protected static function user(): ?\App\Models\User
    {
        return Auth::user();
    }

    protected static function allows(?string $permission): bool
    {
        if ($permission === null) {
            return true;
        }
        $user = static::user();

        return $user !== null && $user->hasPermission($permission);
    }

    /**
     * Filament memeriksa tombol & halaman (Buat, Ubah, Hapus) lewat respons otorisasi, bukan can*().
     * Arahkan ke can*() di atas supaya tombol tersembunyi bagi peran yang tidak berhak (mis. Viewer, Tamu).
     */
    public static function getAuthorizationResponse(string|\UnitEnum $action, ?Model $record = null): Response
    {
        $action = $action instanceof \BackedEnum ? (string) $action->value : ($action instanceof \UnitEnum ? $action->name : $action);
        $record = $record instanceof Model && $record->exists ? $record : null;

        $allowed = match ($action) {
            'viewAny' => static::canViewAny(),
            'view' => $record ? static::canView($record) : static::canViewAny(),
            'create', 'replicate', 'reorder' => static::canCreate(),
            'deleteAny', 'forceDeleteAny', 'restoreAny' => static::canDeleteAny(),
            'delete', 'forceDelete', 'restore' => $record ? static::canDelete($record) : static::canDeleteAny(),
            default => $record ? static::canEdit($record) : static::canCreate(),
        };

        return $allowed ? Response::allow() : Response::deny();
    }

    public static function canViewAny(): bool
    {
        return static::allows(static::$viewPermission);
    }

    public static function canView(Model $record): bool
    {
        return static::allows(static::$viewPermission);
    }

    public static function canCreate(): bool
    {
        return static::allows(static::$editPermission);
    }

    public static function canEdit(Model $record): bool
    {
        return static::allows(static::$editPermission);
    }

    public static function canDelete(Model $record): bool
    {
        return static::canEdit($record);
    }

    public static function canDeleteAny(): bool
    {
        return static::allows(static::$editPermission);
    }

    public static function getNavigationGroup(): ?string
    {
        $key = static::$navigationGroupKey ?? null;

        return $key ? (in_array($key, Terms::PHASES, true) ? Terms::get($key) : $key) : null;
    }
}
