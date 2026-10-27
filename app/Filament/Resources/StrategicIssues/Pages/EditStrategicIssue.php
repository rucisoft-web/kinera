<?php

namespace App\Filament\Resources\StrategicIssues\Pages;

use App\Filament\Resources\StrategicIssues\StrategicIssueResource;
use Filament\Resources\Pages\EditRecord;

class EditStrategicIssue extends EditRecord
{
    protected static string $resource = StrategicIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
