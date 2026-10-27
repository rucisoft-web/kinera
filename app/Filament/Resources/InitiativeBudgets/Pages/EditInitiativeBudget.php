<?php

namespace App\Filament\Resources\InitiativeBudgets\Pages;

use App\Filament\Resources\InitiativeBudgets\InitiativeBudgetResource;
use Filament\Resources\Pages\EditRecord;

class EditInitiativeBudget extends EditRecord
{
    protected static string $resource = InitiativeBudgetResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()?->hasPermission('initiative.budget')) {
            $data += ['verified_by' => auth()->id(), 'verified_at' => now()];
        }

        return $data;
    }
}
