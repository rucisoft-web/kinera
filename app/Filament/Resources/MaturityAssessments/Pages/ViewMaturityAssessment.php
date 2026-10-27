<?php

namespace App\Filament\Resources\MaturityAssessments\Pages;

use App\Filament\Resources\MaturityAssessments\MaturityAssessmentResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMaturityAssessment extends ViewRecord
{
    protected static string $resource = MaturityAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\EditAction::make()];
    }
}
