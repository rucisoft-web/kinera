<?php

namespace App\Filament\Resources\TowsStrategies\Pages;

use App\Filament\Resources\TowsStrategies\TowsStrategyResource;
use Filament\Resources\Pages\EditRecord;

class EditTowsStrategy extends EditRecord
{
    protected static string $resource = TowsStrategyResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
