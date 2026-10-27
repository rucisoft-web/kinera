<?php

namespace App\Filament\Resources\ObjectiveRelations\Pages;

use App\Filament\Resources\ObjectiveRelations\ObjectiveRelationResource;
use Filament\Resources\Pages\ListRecords;

class ListObjectiveRelations extends ListRecords
{
    protected static string $resource = ObjectiveRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
