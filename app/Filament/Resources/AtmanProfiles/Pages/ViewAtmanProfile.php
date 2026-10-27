<?php

namespace App\Filament\Resources\AtmanProfiles\Pages;

use App\Filament\Resources\AtmanProfiles\AtmanProfileResource;
use Filament\Resources\Pages\ViewRecord;

class ViewAtmanProfile extends ViewRecord
{
    protected static string $resource = AtmanProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
