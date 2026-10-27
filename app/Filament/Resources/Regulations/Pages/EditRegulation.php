<?php

namespace App\Filament\Resources\Regulations\Pages;

use App\Filament\Resources\Regulations\RegulationResource;
use Filament\Resources\Pages\EditRecord;

class EditRegulation extends EditRecord
{
    protected static string $resource = RegulationResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
