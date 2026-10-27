<?php

namespace App\Filament\Resources\StrategicThemes\Pages;

use App\Filament\Resources\StrategicThemes\StrategicThemeResource;
use Filament\Resources\Pages\ListRecords;

class ListStrategicThemes extends ListRecords
{
    protected static string $resource = StrategicThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
