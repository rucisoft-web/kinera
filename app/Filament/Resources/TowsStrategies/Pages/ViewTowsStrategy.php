<?php

namespace App\Filament\Resources\TowsStrategies\Pages;

use App\Filament\Resources\TowsStrategies\TowsStrategyResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTowsStrategy extends ViewRecord
{
    protected static string $resource = TowsStrategyResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
