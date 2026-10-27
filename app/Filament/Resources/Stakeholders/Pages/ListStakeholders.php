<?php

namespace App\Filament\Resources\Stakeholders\Pages;

use App\Filament\Resources\Stakeholders\StakeholderResource;
use Filament\Resources\Pages\ListRecords;

class ListStakeholders extends ListRecords
{
    protected static string $resource = StakeholderResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
