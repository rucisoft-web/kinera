<?php

namespace App\Filament\Resources\StrategicChoices\Pages;

use App\Filament\Resources\StrategicChoices\StrategicChoiceResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStrategicChoice extends ViewRecord
{
    protected static string $resource = StrategicChoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
