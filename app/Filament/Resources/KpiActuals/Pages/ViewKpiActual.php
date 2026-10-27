<?php

namespace App\Filament\Resources\KpiActuals\Pages;

use App\Filament\Resources\KpiActuals\KpiActualResource;
use Filament\Resources\Pages\ViewRecord;

class ViewKpiActual extends ViewRecord
{
    protected static string $resource = KpiActualResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
