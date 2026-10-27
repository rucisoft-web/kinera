<?php

namespace App\Filament\Resources\PestelAnalyses\Pages;

use App\Filament\Resources\PestelAnalyses\PestelAnalysisResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPestelAnalysis extends ViewRecord
{
    protected static string $resource = PestelAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
