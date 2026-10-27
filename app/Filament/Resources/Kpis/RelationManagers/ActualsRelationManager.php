<?php

namespace App\Filament\Resources\Kpis\RelationManagers;

use App\Filament\Concerns\HasBscPermissions;
use App\Filament\Support\F;
use App\Support\Fmt;
use App\Support\Terms;
use App\Models;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActualsRelationManager extends RelationManager
{
    protected static string $relationship = 'actuals';

    protected static ?string $title = 'Realisasi bulanan';

    protected static ?string $recordTitleAttribute = 'month';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('actual')->disabled(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('month')->label('Bulan')->formatStateUsing(fn ($state) => Terms::months()[$state] ?? $state)->sortable(),
                TextColumn::make('actual')->label('Realisasi')->formatStateUsing(fn ($state, $record) => Fmt::kpiValue($state, $record->kpi?->unit, $record->kpi?->decimals ?? 0))->alignEnd(),
                TextColumn::make('target')->label('Target')->state(fn ($record) => Fmt::kpiValue($record->target, $record->kpi?->unit, $record->kpi?->decimals ?? 0))->alignEnd(),
                F::scoreColumn('score')->state(fn ($record) => $record->score),
                F::statusColumn('status', Models\KpiActual::STATUSES, ['draf' => 'gray', 'terverifikasi' => 'info', 'disetujui' => 'success', 'dikembalikan' => 'danger']),
                IconColumn::make('is_projection')->label('Proyeksi')->boolean()->trueIcon('heroicon-o-exclamation-triangle')->trueColor('warning')->falseIcon('heroicon-o-document-check')->falseColor('success'),
            ])
            ->filters([

            ])
            ->headerActions([

            ])
            ->recordActions([
                Action::make('buka')->label('Buka')->icon('heroicon-o-arrow-top-right-on-square')->url(fn ($record) => \App\Filament\Resources\KpiActuals\KpiActualResource::getUrl('view', ['record' => $record])),
            ]);
    }

}
