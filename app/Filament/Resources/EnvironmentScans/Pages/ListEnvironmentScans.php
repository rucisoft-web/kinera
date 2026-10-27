<?php

namespace App\Filament\Resources\EnvironmentScans\Pages;

use App\Filament\Resources\EnvironmentScans\EnvironmentScanResource;
use Filament\Resources\Pages\ListRecords;

class ListEnvironmentScans extends ListRecords
{
    protected static string $resource = EnvironmentScanResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
