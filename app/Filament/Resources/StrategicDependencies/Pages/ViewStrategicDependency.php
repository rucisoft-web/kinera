<?php

namespace App\Filament\Resources\StrategicDependencies\Pages;

use App\Filament\Resources\StrategicDependencies\StrategicDependencyResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStrategicDependency extends ViewRecord
{
    protected static string $resource = StrategicDependencyResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
