<?php

namespace App\Filament\Resources\StrategicChoices\Pages;

use App\Filament\Resources\StrategicChoices\StrategicChoiceResource;
use Filament\Resources\Pages\ListRecords;

class ListStrategicChoices extends ListRecords
{
    protected static string $resource = StrategicChoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
