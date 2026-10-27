<?php

namespace App\Filament\Resources\PestelAnalyses\Pages;

use App\Filament\Resources\PestelAnalyses\PestelAnalysisResource;
use Filament\Resources\Pages\ListRecords;

class ListPestelAnalyses extends ListRecords
{
    protected static string $resource = PestelAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
