<?php

namespace App\Filament\Resources\SwotItems\Pages;

use App\Filament\Resources\SwotItems\SwotItemResource;
use Filament\Resources\Pages\EditRecord;

class EditSwotItem extends EditRecord
{
    protected static string $resource = SwotItemResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
