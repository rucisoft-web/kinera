<?php

namespace App\Filament\Resources\StrategicDependencies\Pages;

use App\Filament\Resources\StrategicDependencies\StrategicDependencyResource;
use Filament\Resources\Pages\EditRecord;

class EditStrategicDependency extends EditRecord
{
    protected static string $resource = StrategicDependencyResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
