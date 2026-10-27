<?php

namespace App\Filament\Resources\MarketAnalyses\Pages;

use App\Filament\Resources\MarketAnalyses\MarketAnalysisResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMarketAnalysis extends ViewRecord
{
    protected static string $resource = MarketAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
