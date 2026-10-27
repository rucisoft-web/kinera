<?php

namespace App\Filament\Resources\Findings\Pages;

use App\Filament\Resources\Findings\FindingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinding extends CreateRecord
{
    protected static string $resource = FindingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data + ['raised_by' => auth()->id(), 'raised_at' => now()->toDateString(), 'status' => 'terbuka'];
    }
}
