<?php

namespace App\Filament\Resources\PerformanceContracts\Pages;

use App\Filament\Resources\PerformanceContracts\PerformanceContractResource;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

class ViewPerformanceContract extends ViewRecord
{
    protected static string $resource = PerformanceContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make(PerformanceContractResource::printActions())->label('Cetak')->icon('heroicon-o-printer')->button()->color('gray'),
            \Filament\Actions\EditAction::make(),
        ];
    }
}
