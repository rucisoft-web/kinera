<?php

namespace App\Filament\Resources\RoleAssignments\Pages;

use App\Filament\Resources\RoleAssignments\RoleAssignmentResource;
use Filament\Resources\Pages\ListRecords;

class ListRoleAssignments extends ListRecords
{
    protected static string $resource = RoleAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
