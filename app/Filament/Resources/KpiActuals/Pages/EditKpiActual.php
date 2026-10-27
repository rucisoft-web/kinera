<?php

namespace App\Filament\Resources\KpiActuals\Pages;

use App\Filament\Resources\KpiActuals\KpiActualResource;
use Filament\Resources\Pages\EditRecord;

class EditKpiActual extends EditRecord
{
    protected static string $resource = KpiActualResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\ViewAction::make(), \Filament\Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // perbaikan atas realisasi yang dikembalikan -> kembali ke draf
        if ($this->record->status === 'dikembalikan') {
            $data += ['status' => 'draf', 'submitted_by' => auth()->id(), 'submitted_at' => now(), 'return_note' => $this->record->return_note.' [diperbaiki '.now()->format('d/m/Y').']'];
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->wasChanged('status') && $this->record->status === 'draf') {
            $svc = app(\App\Services\KpiApprovalService::class);
            $this->record->kpi->is_financial ? $svc->notifyVerifiers($this->record) : $svc->notifyApprovers($this->record, 'Realisasi diperbaiki, menunggu persetujuan');
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
