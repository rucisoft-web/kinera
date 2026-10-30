<?php

namespace App\Filament\Pages;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Support\BscContext;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PilihKonteks extends Page
{
    protected static ?string $title = 'Pilih Instansi & Perioda';

    protected static ?string $navigationLabel = 'Pilih Instansi & Perioda';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'Umum';

    protected static ?int $navigationSort = 6;

    protected static ?string $slug = 'pilih-konteks';

    protected string $view = 'filament.pages.pilih-konteks';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('context.switch');
    }

    public function pilih(int $periodId): void
    {
        $user = auth()->user();
        $period = AnalysisPeriod::findOrFail($periodId);
        abort_unless(in_array((int) $period->company_id, $user->accessibleCompanyIds(), true), 403);
        app(BscContext::class)->switch($period->company_id, $period->id);
        Notification::make()->title('Konteks aktif: '.$period->company->code.' · '.$period->name)->success()->send();
        $this->redirect(Beranda::getUrl());
    }

    protected function getViewData(): array
    {
        $ids = auth()->user()->accessibleCompanyIds();
        $ctx = app(BscContext::class);

        return [
            'companies' => Company::with(['periods' => fn ($q) => $q->orderBy('year')])->whereIn('id', $ids)->get(),
            'currentPeriod' => $ctx->periodId(),
            'single' => count($ids) === 1,
        ];
    }
}
