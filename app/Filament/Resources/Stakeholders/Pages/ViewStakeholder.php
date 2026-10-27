<?php

namespace App\Filament\Resources\Stakeholders\Pages;

use App\Filament\Resources\Stakeholders\StakeholderResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStakeholder extends ViewRecord
{
    protected static string $resource = StakeholderResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
