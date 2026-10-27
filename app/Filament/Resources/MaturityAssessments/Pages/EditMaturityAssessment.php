<?php

namespace App\Filament\Resources\MaturityAssessments\Pages;

use App\Filament\Resources\MaturityAssessments\MaturityAssessmentResource;
use Filament\Resources\Pages\EditRecord;

class EditMaturityAssessment extends EditRecord
{
    protected static string $resource = MaturityAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }
}
