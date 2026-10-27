<?php

namespace App\Filament\Resources\StrategicInitiatives\Pages;

use App\Filament\Resources\StrategicInitiatives\StrategicInitiativeResource;
use Filament\Resources\Pages\EditRecord;

class EditStrategicInitiative extends EditRecord
{
    protected static string $resource = StrategicInitiativeResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
