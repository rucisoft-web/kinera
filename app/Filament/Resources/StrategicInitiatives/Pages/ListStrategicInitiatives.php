<?php

namespace App\Filament\Resources\StrategicInitiatives\Pages;

use App\Filament\Resources\StrategicInitiatives\StrategicInitiativeResource;
use Filament\Resources\Pages\ListRecords;

class ListStrategicInitiatives extends ListRecords
{
    protected static string $resource = StrategicInitiativeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
