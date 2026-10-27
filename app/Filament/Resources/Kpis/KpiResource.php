<?php

namespace App\Filament\Resources\Kpis;

use App\Filament\Resources\Kpis\Pages\ListKpis;
use App\Filament\Resources\Kpis\Pages\CreateKpi;
use App\Filament\Resources\Kpis\Pages\ViewKpi;
use App\Filament\Resources\Kpis\Pages\EditKpi;
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

class KpiResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\Kpi::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'KPI';

    protected static ?string $pluralModelLabel = 'KPI';

    protected static ?string $navigationLabel = 'KPI';

    protected static ?string $navigationGroupKey = 'karma_phala';

    protected static ?string $viewPermission = 'kpi.view';

    protected static ?string $editPermission = 'kpi.edit';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Definisi')->columns(3)->schema([
                TextInput::make('name')->label('Nama KPI')->required()->columnSpan(2),
                Select::make('strategic_objective_id')->label('Sasaran')->relationship('objective', 'title')->required()->searchable()->preload(),
                Select::make('unit')->label('Satuan')->options(fn ($state) => Models\Kpi::UNITS + ($state ? [$state => $state] : []))->required()->native(false),
                Select::make('direction')->label('Arah')->options(['higher' => 'Makin besar makin baik', 'lower' => 'Makin kecil makin baik'])->required()->native(false),
                Select::make('aggregation')->label('Agregasi YTD')->options(Models\Kpi::AGGREGATIONS)->required()->native(false),
                F::user('owner_id', 'Pemilik KPI')->required(),
                TextInput::make('weight')->label('Bobot dalam sasaran')->numeric()->default(1),
                TextInput::make('decimals')->label('Desimal tampilan')->numeric()->default(0),
                TextInput::make('baseline')->label('Baseline')->numeric(),
                TextInput::make('annual_target')->label('Target tahunan')->numeric(),
                Select::make('frequency')->label('Frekuensi')->options(Models\Kpi::FREQUENCIES)->default('bulanan')->native(false),
                Toggle::make('is_financial')->label('KPI keuangan (wajib verifikasi Keuangan)'),
                Toggle::make('is_critical')->label('KPI kritis'),
                Toggle::make('is_active')->label('Aktif')->default(true),
                Textarea::make('formula')->label('Rumus')->rows(2)->columnSpanFull(),
                TextInput::make('data_source')->label('Sumber data')->columnSpanFull(),
                Textarea::make('description')->label('Keterangan')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                TextColumn::make('name')->label('KPI')->wrap()->searchable()->description(fn ($record) => $record->objective?->title),
                TextColumn::make('unit')->label('Satuan'),
                TextColumn::make('direction')->label('Arah')->formatStateUsing(fn ($state) => $state === 'lower' ? '↓' : '↑')->alignCenter(),
                TextColumn::make('owner.name')->label('Pemilik')->searchable(),
                IconColumn::make('is_financial')->label('Keu.')->boolean()->falseIcon('heroicon-o-minus')->falseColor('gray'),
                F::scoreColumn('ytd_score', 'Skor YTD')->state(fn ($record) => static::ytd($record)['score']),
                TextColumn::make('ytd_actual')->label('Realisasi YTD')->state(fn ($record) => Fmt::kpiValue(static::ytd($record)['actual'], $record->unit, $record->decimals))->alignEnd(),
                TextColumn::make('ytd_target')->label('Target YTD')->state(fn ($record) => Fmt::kpiValue(static::ytd($record)['target'], $record->unit, $record->decimals))->alignEnd()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('strategic_objective_id')->label('Sasaran')->relationship('objective', 'title'),
                SelectFilter::make('owner_id')->label('Pemilik')->relationship('owner', 'name'),
                TernaryFilter::make('is_financial')->label('KPI keuangan'),
                Filter::make('mine')->label('KPI milik saya')->query(fn (Builder $query) => $query->where('owner_id', Auth::id())),
            ])
            ->defaultSort('code', 'asc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['targets', 'actuals', 'owner', 'objective']))
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
            RelationManagers\TargetsRelationManager::class,
            RelationManagers\ActualsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKpis::route('/'),
            'create' => CreateKpi::route('/create'),
            'view' => ViewKpi::route('/{record}'),
            'edit' => EditKpi::route('/{record}/edit'),
        ];
    }
    public static function ytd($record): array
    {
        return app(\App\Services\KpiScoringService::class)->ytd($record);
    }
}
