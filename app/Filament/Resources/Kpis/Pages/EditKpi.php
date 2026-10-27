<?php

namespace App\Filament\Resources\Kpis\Pages;

use App\Filament\Resources\Kpis\KpiResource;
use Filament\Resources\Pages\EditRecord;

class EditKpi extends EditRecord
{
    protected static string $resource = KpiResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
