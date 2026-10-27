<?php

namespace App\Filament\Resources\EnvironmentScans\Pages;

use App\Filament\Resources\EnvironmentScans\EnvironmentScanResource;
use Filament\Resources\Pages\EditRecord;

class EditEnvironmentScan extends EditRecord
{
    protected static string $resource = EnvironmentScanResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
