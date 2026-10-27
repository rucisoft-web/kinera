<?php

namespace App\Filament\Resources\IndustryForces\Pages;

use App\Filament\Resources\IndustryForces\IndustryForceResource;
use Filament\Resources\Pages\ListRecords;

class ListIndustryForces extends ListRecords
{
    protected static string $resource = IndustryForceResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
