<?php

namespace App\Filament\Resources\Competitors\Pages;

use App\Filament\Resources\Competitors\CompetitorResource;
use Filament\Resources\Pages\EditRecord;

class EditCompetitor extends EditRecord
{
    protected static string $resource = CompetitorResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
