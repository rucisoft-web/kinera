<?php

namespace App\Filament\Resources\Kpis\Pages;

use App\Filament\Resources\Kpis\KpiResource;
use Filament\Resources\Pages\ViewRecord;

class ViewKpi extends ViewRecord
{
    protected static string $resource = KpiResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
