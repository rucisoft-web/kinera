<?php

namespace App\Filament\Resources\StrategicChoices\Pages;

use App\Filament\Resources\StrategicChoices\StrategicChoiceResource;
use Filament\Resources\Pages\EditRecord;

class EditStrategicChoice extends EditRecord
{
    protected static string $resource = StrategicChoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
