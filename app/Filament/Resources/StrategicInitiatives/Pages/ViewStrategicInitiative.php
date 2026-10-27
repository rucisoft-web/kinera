<?php

namespace App\Filament\Resources\StrategicInitiatives\Pages;

use App\Filament\Resources\StrategicInitiatives\StrategicInitiativeResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStrategicInitiative extends ViewRecord
{
    protected static string $resource = StrategicInitiativeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
