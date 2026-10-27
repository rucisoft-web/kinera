<?php

namespace App\Filament\Resources\AtmanProfiles\Pages;

use App\Filament\Resources\AtmanProfiles\AtmanProfileResource;
use Filament\Resources\Pages\EditRecord;

class EditAtmanProfile extends EditRecord
{
    protected static string $resource = AtmanProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
