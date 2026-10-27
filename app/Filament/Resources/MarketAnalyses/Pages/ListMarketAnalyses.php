<?php

namespace App\Filament\Resources\MarketAnalyses\Pages;

use App\Filament\Resources\MarketAnalyses\MarketAnalysisResource;
use Filament\Resources\Pages\ListRecords;

class ListMarketAnalyses extends ListRecords
{
    protected static string $resource = MarketAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
