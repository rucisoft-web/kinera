<?php

namespace App\Filament\Support;

use App\Models\User;
use App\Support\Fmt;
use App\Support\Terms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

/** Komponen form/tabel yang dipakai berulang. */
class F
{
    public static function perspective(string $name = 'perspective'): Select
    {
        return Select::make($name)->label('Perspektif')->options(Terms::perspectives())->native(false);
    }

    public static function user(string $name, string $label = 'Penanggung jawab'): Select
    {
        return Select::make($name)->label($label)->native(false)->searchable()->preload()
            ->options(fn () => User::where('is_active', true)->orderBy('name')->get()->mapWithKeys(fn ($u) => [$u->id => $u->name.($u->job_title ? ' — '.$u->job_title : '')]));
    }

    public static function scale(string $name, string $label, ?string $hint = null): Select
    {
        return Select::make($name)->label($label)->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])->default(3)->helperText($hint)->native(false);
    }

    public static function money(string $name, string $label): TextInput
    {
        return TextInput::make($name)->label($label)->numeric()->prefix('Rp')->minValue(0);
    }

    public static function perspectiveColumn(string $name = 'perspective'): TextColumn
    {
        return TextColumn::make($name)->label('Perspektif')->badge()
            ->formatStateUsing(fn ($state) => Terms::perspective($state))
            ->color(fn ($state) => match ($state) { 'keuangan' => 'danger', 'pelanggan' => 'warning', 'proses' => 'info', 'sdm' => 'success', default => 'gray' });
    }

    public static function code(string $name = 'code'): TextColumn
    {
        return TextColumn::make($name)->label('Kode')->formatStateUsing(fn ($state) => Fmt::shortCode($state))
            ->tooltip(fn ($state) => $state)->searchable()->sortable();
    }

    public static function statusColumn(string $name, array $labels, array $colors, string $label = 'Status'): TextColumn
    {
        return TextColumn::make($name)->label($label)->badge()
            ->formatStateUsing(fn ($state) => $labels[$state] ?? $state)
            ->color(fn ($state) => $colors[$state] ?? 'gray');
    }

    public static function scoreColumn(string $name, string $label = 'Skor'): TextColumn
    {
        return TextColumn::make($name)->label($label)->badge()
            ->formatStateUsing(fn ($state) => $state === null ? '-' : Fmt::num($state, 1))
            ->color(fn ($state) => Fmt::statusColor(app(\App\Services\KpiScoringService::class)->status($state === null ? null : (float) $state)));
    }
}
