<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Support\BscContext;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /** Pengguna yang dibuat admin instansi langsung terdaftar di instansi aktif. */
    protected function afterCreate(): void
    {
        if (! auth()->user()?->isSuperAdmin() && ($companyId = app(BscContext::class)->companyId())) {
            $this->record->companies()->syncWithoutDetaching([$companyId]);
        }
    }
}
