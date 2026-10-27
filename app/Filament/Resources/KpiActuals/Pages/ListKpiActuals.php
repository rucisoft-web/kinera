<?php

namespace App\Filament\Resources\KpiActuals\Pages;

use App\Filament\Resources\KpiActuals\KpiActualResource;
use Filament\Resources\Pages\ListRecords;

class ListKpiActuals extends ListRecords
{
    protected static string $resource = KpiActualResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
