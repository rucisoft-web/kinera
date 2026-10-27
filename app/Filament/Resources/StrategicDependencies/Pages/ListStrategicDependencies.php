<?php

namespace App\Filament\Resources\StrategicDependencies\Pages;

use App\Filament\Resources\StrategicDependencies\StrategicDependencyResource;
use Filament\Resources\Pages\ListRecords;

class ListStrategicDependencies extends ListRecords
{
    protected static string $resource = StrategicDependencyResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
