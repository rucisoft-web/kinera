<?php

namespace App\Filament\Resources\CorrectiveActions\Pages;

use App\Filament\Resources\CorrectiveActions\CorrectiveActionResource;
use Filament\Resources\Pages\ListRecords;

class ListCorrectiveActions extends ListRecords
{
    protected static string $resource = CorrectiveActionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
