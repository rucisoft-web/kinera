<?php

namespace App\Filament\Resources\Findings\Pages;

use App\Filament\Resources\Findings\FindingResource;
use Filament\Resources\Pages\ViewRecord;

class ViewFinding extends ViewRecord
{
    protected static string $resource = FindingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('tutup')->label('Tutup temuan')->icon('heroicon-o-check-circle')->color('success')
                ->visible(fn () => $this->record->status !== 'ditutup' && auth()->user()?->hasPermission('finding.close'))
                ->disabled(fn () => ! $this->record->allActionsDone())
                ->form([\Filament\Forms\Components\Textarea::make('closure_note')->label('Catatan penutupan')->required()])
                ->action(function (array $data) {
                    abort_unless($this->record->allActionsDone(), 422);
                    $this->record->update(['status' => 'ditutup', 'closed_by' => auth()->id(), 'closed_at' => now(), 'closure_note' => $data['closure_note']]);
                    \Filament\Notifications\Notification::make()->title('Temuan ditutup')->success()->send();
                }),
            \Filament\Actions\EditAction::make(),
        ];
    }
}
