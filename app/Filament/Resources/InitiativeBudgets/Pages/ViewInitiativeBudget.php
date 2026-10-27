<?php

namespace App\Filament\Resources\InitiativeBudgets\Pages;

use App\Filament\Resources\InitiativeBudgets\InitiativeBudgetResource;
use Filament\Resources\Pages\ViewRecord;

class ViewInitiativeBudget extends ViewRecord
{
    protected static string $resource = InitiativeBudgetResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
