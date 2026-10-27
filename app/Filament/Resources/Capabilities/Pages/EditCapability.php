<?php

namespace App\Filament\Resources\Capabilities\Pages;

use App\Filament\Resources\Capabilities\CapabilityResource;
use Filament\Resources\Pages\EditRecord;

class EditCapability extends EditRecord
{
    protected static string $resource = CapabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
