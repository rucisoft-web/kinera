<?php

namespace App\Filament\Pages;

use App\Services\BluContractService;
use App\Support\BscContext;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

/** Capaian Kinerja Pemimpin BLU: perhitungan Lampiran VI, capaian per trajektori, syarat insentif Pasal 14, dan tenggat PER-6/PB/2025. */
class CapaianKinerjaBlu extends Page
{
    protected static ?string $title = 'Capaian Kinerja & Insentif BLU';

    protected static ?string $navigationLabel = 'Capaian & Insentif (BLU)';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'capaian-kinerja-blu';

    protected string $view = 'filament.pages.capaian-kinerja-blu';

    #[Url]
    public ?int $s = null;

    public static function getNavigationGroup(): ?string
    {
        return \App\Support\Terms::get('karma_phala');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('kpi.view') && \App\Support\BluModule::enabled();
    }

    public function setUpto(int $month): void
    {
        $this->s = in_array($month, [3, 6, 9, 12], true) ? $month : 12;
    }

    /** Bawaan: triwulan terakhir yang sudah berakhir pada perioda berjalan, atau Triwulan IV untuk perioda yang sudah ditutup. */
    protected function defaultUpto(): int
    {
        $last = app(BscContext::class)->lastClosedMonth();

        return max(3, intdiv(max(3, min(12, $last)), 3) * 3);
    }

    protected function getViewData(): array
    {
        $svc = app(BluContractService::class);
        $upto = $this->s ?: $this->defaultUpto();
        $a = $svc->achievements($upto);

        return [
            'upto' => $upto,
            'a' => $a,
            'checks' => $svc->incentiveChecks($upto === 12 ? $a : $svc->achievements(12)),
            'quarterly' => $svc->quarterly(),
            'deadlines' => $svc->deadlines(),
            'company' => app(BscContext::class)->company(),
            'period' => app(BscContext::class)->period(),
        ];
    }
}
