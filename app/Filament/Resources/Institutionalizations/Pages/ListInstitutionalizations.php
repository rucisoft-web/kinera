<?php

namespace App\Filament\Resources\Institutionalizations\Pages;

use App\Filament\Resources\Institutionalizations\InstitutionalizationResource;
use Filament\Resources\Pages\ListRecords;

class ListInstitutionalizations extends ListRecords
{
    protected static string $resource = InstitutionalizationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
