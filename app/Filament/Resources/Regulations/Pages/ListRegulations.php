<?php

namespace App\Filament\Resources\Regulations\Pages;

use App\Filament\Resources\Regulations\RegulationResource;
use Filament\Resources\Pages\ListRecords;

class ListRegulations extends ListRecords
{
    protected static string $resource = RegulationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
