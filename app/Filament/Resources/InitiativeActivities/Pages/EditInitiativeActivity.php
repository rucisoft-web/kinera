<?php

namespace App\Filament\Resources\InitiativeActivities\Pages;

use App\Filament\Resources\InitiativeActivities\InitiativeActivityResource;
use Filament\Resources\Pages\EditRecord;

class EditInitiativeActivity extends EditRecord
{
    protected static string $resource = InitiativeActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
