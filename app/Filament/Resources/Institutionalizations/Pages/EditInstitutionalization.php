<?php

namespace App\Filament\Resources\Institutionalizations\Pages;

use App\Filament\Resources\Institutionalizations\InstitutionalizationResource;
use Filament\Resources\Pages\EditRecord;

class EditInstitutionalization extends EditRecord
{
    protected static string $resource = InstitutionalizationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
