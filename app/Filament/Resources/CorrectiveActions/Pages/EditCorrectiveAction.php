<?php

namespace App\Filament\Resources\CorrectiveActions\Pages;

use App\Filament\Resources\CorrectiveActions\CorrectiveActionResource;
use Filament\Resources\Pages\EditRecord;

class EditCorrectiveAction extends EditRecord
{
    protected static string $resource = CorrectiveActionResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
