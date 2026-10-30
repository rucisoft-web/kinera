<?php

namespace App\Filament\Pages;

use App\Support\Bantuan;
use Filament\Pages\Page;
use Livewire\Attributes\Url;

/** Bantuan pengisian per layar; dibuka dari tombol "Bantuan" di kanan atas setiap layar isian. */
class BantuanPengisian extends Page
{
    protected static ?string $title = 'Bantuan Pengisian';

    protected static ?string $navigationLabel = 'Bantuan Pengisian';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static string|\UnitEnum|null $navigationGroup = 'Panduan';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'bantuan';

    protected string $view = 'filament.pages.bantuan-pengisian';

    #[Url]
    public ?string $topik = null;

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public static function urlFor(string $key): string
    {
        return static::getUrl(['topik' => $key]);
    }

    public function getTitle(): string
    {
        return Bantuan::exists($this->topik) ? 'Bantuan: '.Bantuan::registry()[$this->topik][0] : static::$title;
    }

    protected function getViewData(): array
    {
        $groups = [];
        foreach (Bantuan::registry() as $key => [$title, $group, $resource]) {
            if (Bantuan::exists($key)) {
                $groups[\App\Support\Terms::get($group)][$key] = $title;
            }
        }

        $topic = Bantuan::exists($this->topik) ? Bantuan::topic($this->topik) : null;
        $resource = $topic ? Bantuan::registry()[$this->topik][2] : null;

        return [
            'groups' => $groups,
            'topic' => $topic,
            'image' => $topic ? Bantuan::image($this->topik) : null,
            'screenUrl' => $resource && $resource::canViewAny() ? $resource::getUrl('index') : null,
        ];
    }
}
