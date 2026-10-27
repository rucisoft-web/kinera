<?php

namespace App\Filament\Resources\InitiativeActivities\Pages;

use App\Filament\Resources\InitiativeActivities\InitiativeActivityResource;
use Filament\Resources\Pages\ListRecords;

class ListInitiativeActivities extends ListRecords
{
    protected static string $resource = InitiativeActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
