<?php

namespace App\Filament\Resources\Findings\Pages;

use App\Filament\Resources\Findings\FindingResource;
use Filament\Resources\Pages\EditRecord;

class EditFinding extends EditRecord
{
    protected static string $resource = FindingResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
