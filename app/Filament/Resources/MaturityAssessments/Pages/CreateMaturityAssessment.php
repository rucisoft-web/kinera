<?php

namespace App\Filament\Resources\MaturityAssessments\Pages;

use App\Filament\Resources\MaturityAssessments\MaturityAssessmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaturityAssessment extends CreateRecord
{
    protected static string $resource = MaturityAssessmentResource::class;

    protected function afterCreate(): void
    {
        foreach (\App\Models\MaturityQuestion::orderBy('sort')->get() as $q) {
            \App\Models\MaturityAnswer::firstOrCreate(['maturity_assessment_id' => $this->record->id, 'maturity_question_id' => $q->id], ['score' => 1]);
        }
        app(\App\Services\MaturityScoringService::class)->recalculate($this->record);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
