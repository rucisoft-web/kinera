<?php

namespace App\Filament\Resources\IndustryForces\Pages;

use App\Filament\Resources\IndustryForces\IndustryForceResource;
use Filament\Resources\Pages\EditRecord;

class EditIndustryForce extends EditRecord
{
    protected static string $resource = IndustryForceResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
