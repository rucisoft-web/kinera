<?php

namespace App\Filament\Resources\InitiativeMilestones\Pages;

use App\Filament\Resources\InitiativeMilestones\InitiativeMilestoneResource;
use Filament\Resources\Pages\ViewRecord;

class ViewInitiativeMilestone extends ViewRecord
{
    protected static string $resource = InitiativeMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
