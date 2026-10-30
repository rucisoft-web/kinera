<?php

namespace App\Filament\Resources\KpiActuals\Pages;

use App\Filament\Resources\KpiActuals\KpiActualResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKpiActual extends CreateRecord
{
    protected static string $resource = KpiActualResource::class;

    public function mount(): void
    {
        parent::mount();
        $this->form->fill(array_filter([
            'kpi_id' => request()->integer('kpi') ?: null,
            'month' => request()->integer('month') ?: null,
        ]));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $kpi = \App\Models\Kpi::findOrFail($data['kpi_id']);
        abort_unless(app(\App\Services\KpiApprovalService::class)->canInput(auth()->user(), $kpi), 403, 'Anda hanya dapat mengisi realisasi IK milik Anda.');
        if (\App\Models\KpiActual::where('kpi_id', $kpi->id)->where('month', $data['month'])->exists()) {
            \Filament\Notifications\Notification::make()->title('Realisasi bulan ini sudah ada — silakan ubah baris yang ada.')->danger()->send();
            $this->halt();
        }

        return $data + ['status' => 'draf', 'submitted_by' => auth()->id(), 'submitted_at' => now()];
    }

    protected function afterCreate(): void
    {
        $svc = app(\App\Services\KpiApprovalService::class);
        $this->record->kpi->is_financial ? $svc->notifyVerifiers($this->record) : $svc->notifyApprovers($this->record, 'Realisasi baru menunggu persetujuan');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
