<?php

namespace App\Filament\Resources\StrategicInitiatives;

use App\Filament\Resources\StrategicInitiatives\Pages\ListStrategicInitiatives;
use App\Filament\Resources\StrategicInitiatives\Pages\CreateStrategicInitiative;
use App\Filament\Resources\StrategicInitiatives\Pages\ViewStrategicInitiative;
use App\Filament\Resources\StrategicInitiatives\Pages\EditStrategicInitiative;
use App\Filament\Concerns\HasDhastramPermissions;
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
use Filament\Resources\Resource;
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

class StrategicInitiativeResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\StrategicInitiative::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Inisiatif Strategis';

    protected static ?string $pluralModelLabel = 'Inisiatif Strategis';

    protected static ?string $navigationLabel = 'Inisiatif Strategis';

    protected static ?string $navigationGroupKey = 'punarbhawa';

    protected static ?string $viewPermission = 'initiative.view';

    protected static ?string $editPermission = 'initiative.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Inisiatif')->columns(3)->schema([
                TextInput::make('title')->label('Inisiatif')->required()->columnSpan(2),
                Select::make('status')->options(Models\StrategicInitiative::STATUSES)->default('direncanakan')->native(false),
                Textarea::make('description')->label('Uraian')->rows(3)->columnSpanFull(),
                Select::make('strategic_theme_id')->label('Tema')->relationship('theme', 'name')->native(false),
                F::user('owner_id', 'PIC inisiatif')->required(),
                Select::make('objectives')->label('Sasaran yang didukung')->relationship('objectives', 'title')->multiple()->preload(),
                DatePicker::make('start_date')->label('Mulai'),
                DatePicker::make('end_date')->label('Selesai')->afterOrEqual('start_date'),
                Placeholder::make('progress_info')->label('Progres (otomatis)')->content(fn ($record) => $record ? Fmt::num($record->progress, 1).'% — dari aktivitas (70%) & milestone (30%)' : 'Dihitung otomatis'),
                Textarea::make('expected_outcome')->label('Hasil yang diharapkan')->rows(2)->columnSpanFull(),
            ]),
            Section::make('Penilaian portofolio')->description('Skala 1 = sangat rendah … 5 = sangat tinggi. Nilai = dampak × 2 + urgensi · Upaya = risiko + tingkat anggaran')->columns(4)->schema([
                F::scale('impact', 'Dampak'),
                F::scale('urgency', 'Urgensi'),
                F::scale('risk', 'Risiko'),
                F::scale('budget_level', 'Tingkat anggaran'),
                F::money('budget_planned', 'Total anggaran rencana')->helperText('Diperbarui dari rincian anggaran.')->columnSpan(2),
                Textarea::make('notes')->label('Catatan')->rows(2)->columnSpan(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                TextColumn::make('title')->label('Inisiatif')->wrap()->searchable()->description(fn ($record) => $record->owner?->name),
                F::statusColumn('status', Models\StrategicInitiative::STATUSES, ['direncanakan' => 'gray', 'berjalan' => 'info', 'tertunda' => 'warning', 'selesai' => 'success', 'dibatalkan' => 'gray']),
                TextColumn::make('progress')->label('Progres')->formatStateUsing(fn ($state) => Fmt::num($state).'%')->sortable()->alignEnd()
                    ->color(fn ($record) => app(\App\Services\InitiativeProgressService::class)->isLate($record) ? 'danger' : null)
                    ->description(fn ($record) => 'waktu '.Fmt::num(app(\App\Services\InitiativeProgressService::class)->timeElapsed($record)).'%'),
                TextColumn::make('end_date')->label('Selesai')->date('d M Y')->sortable(),
                TextColumn::make('quadrant')->label('Kuadran')->state(fn ($record) => \App\Services\InitiativePortfolioService::QUADRANTS[app(\App\Services\InitiativePortfolioService::class)->quadrant($record)]['label'])->badge(),
                TextColumn::make('budget_planned')->label('Anggaran')->formatStateUsing(fn ($state) => Fmt::rp($state))->alignEnd()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(Models\StrategicInitiative::STATUSES),
                SelectFilter::make('owner_id')->label('PIC')->relationship('owner', 'name'),
                Filter::make('mine')->label('Inisiatif saya')->query(fn (Builder $query) => $query->where('owner_id', Auth::id())),
            ])
            ->defaultSort('code', 'asc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ActivitiesRelationManager::class,
            RelationManagers\MilestonesRelationManager::class,
            RelationManagers\BudgetsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStrategicInitiatives::route('/'),
            'create' => CreateStrategicInitiative::route('/create'),
            'view' => ViewStrategicInitiative::route('/{record}'),
            'edit' => EditStrategicInitiative::route('/{record}/edit'),
        ];
    }

}
