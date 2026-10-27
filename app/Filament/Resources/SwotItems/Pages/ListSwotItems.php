<?php

namespace App\Filament\Resources\SwotItems\Pages;

use App\Filament\Resources\SwotItems\SwotItemResource;
use Filament\Resources\Pages\ListRecords;

class ListSwotItems extends ListRecords
{
    protected static string $resource = SwotItemResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
