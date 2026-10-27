<?php

namespace App\Filament\Resources\IndustryForces\Pages;

use App\Filament\Resources\IndustryForces\IndustryForceResource;
use Filament\Resources\Pages\ViewRecord;

class ViewIndustryForce extends ViewRecord
{
    protected static string $resource = IndustryForceResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
