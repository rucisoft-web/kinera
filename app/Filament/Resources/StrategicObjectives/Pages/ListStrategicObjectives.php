<?php

namespace App\Filament\Resources\StrategicObjectives\Pages;

use App\Filament\Resources\StrategicObjectives\StrategicObjectiveResource;
use Filament\Resources\Pages\ListRecords;

class ListStrategicObjectives extends ListRecords
{
    protected static string $resource = StrategicObjectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
