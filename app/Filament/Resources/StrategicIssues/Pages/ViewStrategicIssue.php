<?php

namespace App\Filament\Resources\StrategicIssues\Pages;

use App\Filament\Resources\StrategicIssues\StrategicIssueResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStrategicIssue extends ViewRecord
{
    protected static string $resource = StrategicIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
