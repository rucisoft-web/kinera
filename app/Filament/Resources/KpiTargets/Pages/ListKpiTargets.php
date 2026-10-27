<?php

namespace App\Filament\Resources\KpiTargets\Pages;

use App\Filament\Resources\KpiTargets\KpiTargetResource;
use Filament\Resources\Pages\ListRecords;

class ListKpiTargets extends ListRecords
{
    protected static string $resource = KpiTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
