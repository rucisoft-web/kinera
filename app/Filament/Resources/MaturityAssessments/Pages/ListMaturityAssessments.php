<?php

namespace App\Filament\Resources\MaturityAssessments\Pages;

use App\Filament\Resources\MaturityAssessments\MaturityAssessmentResource;
use Filament\Resources\Pages\ListRecords;

class ListMaturityAssessments extends ListRecords
{
    protected static string $resource = MaturityAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
