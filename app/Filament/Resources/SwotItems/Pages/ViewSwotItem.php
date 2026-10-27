<?php

namespace App\Filament\Resources\SwotItems\Pages;

use App\Filament\Resources\SwotItems\SwotItemResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSwotItem extends ViewRecord
{
    protected static string $resource = SwotItemResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
