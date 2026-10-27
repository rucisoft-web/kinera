<?php

namespace App\Filament\Resources\PerformanceContracts\Pages;

use App\Filament\Resources\PerformanceContracts\PerformanceContractResource;
use Filament\Resources\Pages\EditRecord;

class EditPerformanceContract extends EditRecord
{
    protected static string $resource = PerformanceContractResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), PerformanceContractResource::refreshAction(), \Filament\Actions\DeleteAction::make()];
    }
}
