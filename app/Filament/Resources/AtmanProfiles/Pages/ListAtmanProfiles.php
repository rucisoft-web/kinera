<?php

namespace App\Filament\Resources\AtmanProfiles\Pages;

use App\Filament\Resources\AtmanProfiles\AtmanProfileResource;
use Filament\Resources\Pages\ListRecords;

class ListAtmanProfiles extends ListRecords
{
    protected static string $resource = AtmanProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
