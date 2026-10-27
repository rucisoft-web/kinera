<?php

namespace App\Filament\Resources\Institutionalizations\Pages;

use App\Filament\Resources\Institutionalizations\InstitutionalizationResource;
use Filament\Resources\Pages\ViewRecord;

class ViewInstitutionalization extends ViewRecord
{
    protected static string $resource = InstitutionalizationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
