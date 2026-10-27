<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Support\DhastramContext;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /** Pengguna yang dibuat admin perusahaan langsung terdaftar di perusahaan aktif. */
    protected function afterCreate(): void
    {
        if (! auth()->user()?->isSuperAdmin() && ($companyId = app(DhastramContext::class)->companyId())) {
            $this->record->companies()->syncWithoutDetaching([$companyId]);
        }
    }
}
