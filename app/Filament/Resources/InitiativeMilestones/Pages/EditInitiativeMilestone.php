<?php

namespace App\Filament\Resources\InitiativeMilestones\Pages;

use App\Filament\Resources\InitiativeMilestones\InitiativeMilestoneResource;
use Filament\Resources\Pages\EditRecord;

class EditInitiativeMilestone extends EditRecord
{
    protected static string $resource = InitiativeMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
