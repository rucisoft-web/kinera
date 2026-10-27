<?php

namespace App\Filament\Resources\PerformanceContracts\Pages;

use App\Filament\Resources\PerformanceContracts\PerformanceContractResource;
use App\Models\PerformanceContract;
use App\Services\BluContractService;
use Filament\Resources\Pages\CreateRecord;

class CreatePerformanceContract extends CreateRecord
{
    protected static string $resource = PerformanceContractResource::class;

    /** Versi berikutnya memotret Indikator Kinerja & target trajektori saat ini; perubahan dibandingkan dengan versi terakhir yang ditandatangani. */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $previous = PerformanceContract::where('status', 'ditandatangani')->orderByDesc('version')->first();
        $data['version'] = (int) (PerformanceContract::max('version') ?? -1) + 1;
        if ($data['version'] === 0) {
            $data['kind'] = 'awal';
        } elseif (($data['kind'] ?? 'awal') === 'awal') {
            $data['kind'] = 'perubahan';
        }
        $data['indicators'] = app(BluContractService::class)->snapshot($data['version'] > 0 ? $previous : null);
        $data['created_by'] = auth()->id();

        return $data;
    }
}
