<?php

namespace App\Filament\Resources\TowsStrategies;

use App\Filament\Resources\TowsStrategies\Pages\ListTowsStrategies;
use App\Filament\Resources\TowsStrategies\Pages\CreateTowsStrategy;
use App\Filament\Resources\TowsStrategies\Pages\ViewTowsStrategy;
use App\Filament\Resources\TowsStrategies\Pages\EditTowsStrategy;
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

class TowsStrategyResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\TowsStrategy::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Strategi TOWS';

    protected static ?string $pluralModelLabel = 'TOWS';

    protected static ?string $navigationLabel = 'TOWS';

    protected static ?string $navigationGroupKey = 'brahman';

    protected static ?string $viewPermission = 'brahman.view';

    protected static ?string $editPermission = 'brahman.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                Select::make('type')->label('Kuadran')->options(['SO' => 'SO — kekuatan × peluang', 'WO' => 'WO — kelemahan × peluang', 'ST' => 'ST — kekuatan × ancaman', 'WT' => 'WT — kelemahan × ancaman'])->required()->native(false),
                F::scale('priority', 'Prioritas'),
                TextInput::make('title')->label('Strategi')->required()->columnSpanFull(),
                Textarea::make('description')->label('Uraian')->rows(3)->columnSpanFull(),
                Select::make('status')->options(['diusulkan' => 'Diusulkan', 'dipilih' => 'Dipilih', 'ditolak' => 'Ditolak'])->native(false),
                Select::make('issues')->label('Isu strategis yang dijawab')->relationship('issues', 'title')->multiple()->preload()->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                TextColumn::make('type')->label('Kuadran')->badge()->color(fn ($state) => match ($state) { 'SO' => 'success', 'WO' => 'info', 'ST' => 'warning', default => 'danger' }),
                TextColumn::make('title')->label('Strategi')->searchable()->wrap(),
                TextColumn::make('issues.title')->label('Isu')->listWithLineBreaks()->limitList(2)->expandableLimitedList()->toggleable(),
                TextColumn::make('priority')->label('Prioritas')->sortable(),
                TextColumn::make('status')->badge(),
            ])
            ->filters([
                SelectFilter::make('type')->label('Kuadran')->options(['SO' => 'SO', 'WO' => 'WO', 'ST' => 'ST', 'WT' => 'WT']),
            ])
            ->defaultSort('type', 'asc')
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTowsStrategies::route('/'),
            'create' => CreateTowsStrategy::route('/create'),
            'view' => ViewTowsStrategy::route('/{record}'),
            'edit' => EditTowsStrategy::route('/{record}/edit'),
        ];
    }

}
