<?php

namespace App\Filament\Resources\InitiativeMilestones\Pages;

use App\Filament\Resources\InitiativeMilestones\InitiativeMilestoneResource;
use Filament\Resources\Pages\ListRecords;

class ListInitiativeMilestones extends ListRecords
{
    protected static string $resource = InitiativeMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
