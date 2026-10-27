<?php

namespace App\Filament\Resources\Capabilities\Pages;

use App\Filament\Resources\Capabilities\CapabilityResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCapability extends ViewRecord
{
    protected static string $resource = CapabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
