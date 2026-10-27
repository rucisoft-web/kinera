<?php

namespace App\Filament\Resources\ObjectiveRelations\Pages;

use App\Filament\Resources\ObjectiveRelations\ObjectiveRelationResource;
use Filament\Resources\Pages\EditRecord;

class EditObjectiveRelation extends EditRecord
{
    protected static string $resource = ObjectiveRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
