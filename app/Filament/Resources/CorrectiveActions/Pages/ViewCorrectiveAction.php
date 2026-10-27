<?php

namespace App\Filament\Resources\CorrectiveActions\Pages;

use App\Filament\Resources\CorrectiveActions\CorrectiveActionResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCorrectiveAction extends ViewRecord
{
    protected static string $resource = CorrectiveActionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
