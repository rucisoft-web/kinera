<?php

namespace App\Filament\Resources\PerformanceContracts\Pages;

use App\Filament\Resources\PerformanceContracts\PerformanceContractResource;
use Filament\Resources\Pages\ListRecords;

class ListPerformanceContracts extends ListRecords
{
    protected static string $resource = PerformanceContractResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()->label('Susun Kontrak / Perubahan')];
    }
}
