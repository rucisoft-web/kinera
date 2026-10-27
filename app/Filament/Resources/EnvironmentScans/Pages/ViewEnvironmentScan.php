<?php

namespace App\Filament\Resources\EnvironmentScans\Pages;

use App\Filament\Resources\EnvironmentScans\EnvironmentScanResource;
use Filament\Resources\Pages\ViewRecord;

class ViewEnvironmentScan extends ViewRecord
{
    protected static string $resource = EnvironmentScanResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
