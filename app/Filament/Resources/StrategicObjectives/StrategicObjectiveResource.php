<?php

namespace App\Filament\Resources\StrategicObjectives;

use App\Filament\Resources\StrategicObjectives\Pages\ListStrategicObjectives;
use App\Filament\Resources\StrategicObjectives\Pages\CreateStrategicObjective;
use App\Filament\Resources\StrategicObjectives\Pages\ViewStrategicObjective;
use App\Filament\Resources\StrategicObjectives\Pages\EditStrategicObjective;
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

class StrategicObjectiveResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\StrategicObjective::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-flag';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Sasaran Strategis';

    protected static ?string $pluralModelLabel = 'Sasaran Strategis';

    protected static ?string $navigationLabel = 'Sasaran Strategis';

    protected static ?string $navigationGroupKey = 'karma_phala';

    protected static ?string $viewPermission = 'strategy.view';

    protected static ?string $editPermission = 'strategy.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('title')->label('Sasaran')->required()->columnSpanFull(),
                F::perspective()->required(),
                Select::make('strategic_theme_id')->label('Tema')->relationship('theme', 'name')->native(false),
                F::user('owner_id'),
                TextInput::make('weight')->label('Bobot dalam perspektif')->numeric()->default(1),
                Textarea::make('description')->label('Uraian')->rows(3)->columnSpanFull(),
                TextInput::make('sort')->label('Urutan')->numeric()->default(0),
            ]),
            Section::make('Capaian')->visibleOn('view')->schema([
                Placeholder::make('capaian')->label('')->content(function ($record) {
                    $row = static::scoreFor($record);
                    if (! $row) { return '-'; }
                    return new \Illuminate\Support\HtmlString('<strong>Skor '.Fmt::num($row['score'], 1).'</strong> ('.($row['status'] ?? 'belum ada data').') dari '.$row['kpis']->count().' KPI');
                }),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                F::perspectiveColumn(),
                TextColumn::make('title')->label('Sasaran')->wrap()->searchable()->description(fn ($record) => $record->theme?->name),
                TextColumn::make('owner.name')->label('Penanggung jawab'),
                TextColumn::make('weight')->label('Bobot'),
                TextColumn::make('kpis_count')->label('KPI')->counts('kpis'),
                F::scoreColumn('score_ytd', 'Skor YTD')->state(fn ($record) => static::scoreFor($record)['score'] ?? null),
            ])
            ->filters([
                SelectFilter::make('perspective')->label('Perspektif')->options(Terms::perspectives()),
            ])
            ->defaultSort('sort', 'asc')
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
            RelationManagers\KpisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStrategicObjectives::route('/'),
            'create' => CreateStrategicObjective::route('/create'),
            'view' => ViewStrategicObjective::route('/{record}'),
            'edit' => EditStrategicObjective::route('/{record}/edit'),
        ];
    }
    /** @var array<int,array>|null */
    protected static ?array $scoreCache = null;

    public static function scoreFor($record): ?array
    {
        if (static::$scoreCache === null) {
            static::$scoreCache = app(\App\Services\StrategicScoreService::class)->objectives()->keyBy(fn ($r) => $r['objective']->id)->all();
        }

        return static::$scoreCache[$record->id] ?? null;
    }
}
