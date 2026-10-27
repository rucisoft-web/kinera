<?php

namespace App\Filament\Resources\MarketAnalyses\Pages;

use App\Filament\Resources\MarketAnalyses\MarketAnalysisResource;
use Filament\Resources\Pages\EditRecord;

class EditMarketAnalysis extends EditRecord
{
    protected static string $resource = MarketAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
