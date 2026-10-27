<?php

namespace App\Filament\Resources\KpiTargets\Pages;

use App\Filament\Resources\KpiTargets\KpiTargetResource;
use Filament\Resources\Pages\EditRecord;

class EditKpiTarget extends EditRecord
{
    protected static string $resource = KpiTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
