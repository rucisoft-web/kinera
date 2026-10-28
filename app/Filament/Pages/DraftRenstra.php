<?php

namespace App\Filament\Pages;

use App\Services\RenstraDraftService;
use App\Support\BscContext;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

/** Menyusun Draf Renstra lima tahunan dari isian Kinera, siap dicetak PDF atau diunduh Word. */
class DraftRenstra extends Page
{
    protected static ?string $title = 'Draf Renstra';

    protected static ?string $navigationLabel = 'Draf Renstra';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|\UnitEnum|null $navigationGroup = 'Umum';

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'draf-renstra';

    protected string $view = 'filament.pages.draft-renstra';

    #[Url]
    public ?int $mulai = null;

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasPermission('report.view');
    }

    protected function getViewData(): array
    {
        $svc = app(RenstraDraftService::class);
        $years = $svc->startYears();
        $start = in_array($this->mulai, $years, true) ? $this->mulai : app(BscContext::class)->year();
        $d = $svc->build($start);

        return [
            'years' => $years,
            'start' => $start,
            'd' => $d,
            'printUrl' => route('renstra.print', ['mulai' => $start]),
            'wordUrl' => route('renstra.print', ['mulai' => $start, 'format' => 'word']),
            'checks' => [
                ['Visi, misi, dan tujuan (Jati Diri)', filled($d['profile']?->vision) && filled($d['profile']?->mission)],
                ['Regulasi / landasan hukum', $d['regulations']->isNotEmpty()],
                ['PESTEL dan SWOT', $d['pestel']->isNotEmpty() && $d['swot']->isNotEmpty()],
                ['Isu strategis', $d['issues']->isNotEmpty()],
                ['Strategi TOWS dan tema strategis', $d['tows']->isNotEmpty() && $d['themes']->isNotEmpty()],
                ['Sasaran strategis dengan IKU', $d['kpiRows']->isNotEmpty()],
                ['Baseline dan target IKU', $d['kpiRows']->every(fn ($r) => $r['kpi']->baseline !== null && $r['kpi']->annual_target !== null)],
                ['Program/inisiatif dengan anggaran', $d['funding']->contains(fn ($f) => $f['base'] > 0)],
            ],
        ];
    }
}
