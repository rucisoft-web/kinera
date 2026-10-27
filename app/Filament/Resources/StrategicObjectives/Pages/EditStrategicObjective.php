<?php

namespace App\Filament\Resources\StrategicObjectives\Pages;

use App\Filament\Resources\StrategicObjectives\StrategicObjectiveResource;
use Filament\Resources\Pages\EditRecord;

class EditStrategicObjective extends EditRecord
{
    protected static string $resource = StrategicObjectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
