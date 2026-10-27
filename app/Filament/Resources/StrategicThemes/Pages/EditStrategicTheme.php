<?php

namespace App\Filament\Resources\StrategicThemes\Pages;

use App\Filament\Resources\StrategicThemes\StrategicThemeResource;
use Filament\Resources\Pages\EditRecord;

class EditStrategicTheme extends EditRecord
{
    protected static string $resource = StrategicThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
