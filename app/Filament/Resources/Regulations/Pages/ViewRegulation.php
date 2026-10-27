<?php

namespace App\Filament\Resources\Regulations\Pages;

use App\Filament\Resources\Regulations\RegulationResource;
use Filament\Resources\Pages\ViewRecord;

class ViewRegulation extends ViewRecord
{
    protected static string $resource = RegulationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
