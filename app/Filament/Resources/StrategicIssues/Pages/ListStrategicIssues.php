<?php

namespace App\Filament\Resources\StrategicIssues\Pages;

use App\Filament\Resources\StrategicIssues\StrategicIssueResource;
use Filament\Resources\Pages\ListRecords;

class ListStrategicIssues extends ListRecords
{
    protected static string $resource = StrategicIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
