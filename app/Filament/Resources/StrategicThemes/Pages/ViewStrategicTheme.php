<?php

namespace App\Filament\Resources\StrategicThemes\Pages;

use App\Filament\Resources\StrategicThemes\StrategicThemeResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStrategicTheme extends ViewRecord
{
    protected static string $resource = StrategicThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
