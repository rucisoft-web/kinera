<?php

namespace App\Filament\Resources\InitiativeActivities\Pages;

use App\Filament\Resources\InitiativeActivities\InitiativeActivityResource;
use Filament\Resources\Pages\ViewRecord;

class ViewInitiativeActivity extends ViewRecord
{
    protected static string $resource = InitiativeActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
