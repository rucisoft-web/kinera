<?php

namespace App\Filament\Resources\Stakeholders\Pages;

use App\Filament\Resources\Stakeholders\StakeholderResource;
use Filament\Resources\Pages\EditRecord;

class EditStakeholder extends EditRecord
{
    protected static string $resource = StakeholderResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
