<?php

namespace App\Filament\Resources\PestelAnalyses\Pages;

use App\Filament\Resources\PestelAnalyses\PestelAnalysisResource;
use Filament\Resources\Pages\EditRecord;

class EditPestelAnalysis extends EditRecord
{
    protected static string $resource = PestelAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
