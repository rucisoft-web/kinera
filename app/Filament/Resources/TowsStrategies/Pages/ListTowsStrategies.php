<?php

namespace App\Filament\Resources\TowsStrategies\Pages;

use App\Filament\Resources\TowsStrategies\TowsStrategyResource;
use Filament\Resources\Pages\ListRecords;

class ListTowsStrategies extends ListRecords
{
    protected static string $resource = TowsStrategyResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
