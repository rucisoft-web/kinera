<?php

namespace App\Filament\Resources\StrategicObjectives\Pages;

use App\Filament\Resources\StrategicObjectives\StrategicObjectiveResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStrategicObjective extends ViewRecord
{
    protected static string $resource = StrategicObjectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
