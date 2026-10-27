<?php

namespace App\Filament\Resources\InitiativeBudgets\Pages;

use App\Filament\Resources\InitiativeBudgets\InitiativeBudgetResource;
use Filament\Resources\Pages\ListRecords;

class ListInitiativeBudgets extends ListRecords
{
    protected static string $resource = InitiativeBudgetResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
