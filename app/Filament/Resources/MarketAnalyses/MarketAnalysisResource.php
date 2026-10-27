<?php

namespace App\Filament\Resources\MarketAnalyses;

use App\Filament\Resources\MarketAnalyses\Pages\ListMarketAnalyses;
use App\Filament\Resources\MarketAnalyses\Pages\CreateMarketAnalysis;
use App\Filament\Resources\MarketAnalyses\Pages\ViewMarketAnalysis;
use App\Filament\Resources\MarketAnalyses\Pages\EditMarketAnalysis;
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

class MarketAnalysisResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\MarketAnalysis::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Segmen Pasar';

    protected static ?string $pluralModelLabel = 'Pasar & Pelanggan';

    protected static ?string $navigationLabel = 'Pasar & Pelanggan';

    protected static ?string $navigationGroupKey = 'brahman';

    protected static ?string $viewPermission = 'brahman.view';

    protected static ?string $editPermission = 'brahman.edit';

    protected static ?string $recordTitleAttribute = 'segment';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('segment')->label('Segmen')->required()->columnSpanFull(),
                TextInput::make('segment_group')->label('Kelompok'),
                Select::make('growth')->label('Pertumbuhan')->options(['naik' => 'Naik', 'stabil' => 'Stabil', 'turun' => 'Turun'])->native(false),
                TextInput::make('share_percent')->label('Porsi pendapatan (%)')->numeric()->suffix('%'),
                TextInput::make('revenue_contribution')->label('Kontribusi pendapatan (Rp juta)')->numeric(),
                Toggle::make('is_target')->label('Segmen sasaran')->default(true),
                Textarea::make('description')->label('Uraian & contoh akun')->rows(3)->columnSpanFull(),
                Textarea::make('needs')->label('Kebutuhan pelanggan')->rows(3)->columnSpanFull(),
                Textarea::make('strategy')->label('Strategi')->rows(3)->columnSpanFull(),
                TextInput::make('source')->label('Sumber data')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('segment')->label('Segmen')->searchable()->wrap()->weight('bold'),
                TextColumn::make('segment_group')->label('Kelompok')->badge(),
                TextColumn::make('share_percent')->label('Porsi')->formatStateUsing(fn ($state) => $state ? Fmt::pct($state, 0) : '-')->sortable(),
                TextColumn::make('growth')->label('Tren')->badge()->color(fn ($state) => match ($state) { 'naik' => 'success', 'turun' => 'danger', default => 'gray' }),
                IconColumn::make('is_target')->label('Sasaran')->boolean(),
                TextColumn::make('strategy')->label('Strategi')->limit(60)->wrap()->toggleable(),
            ])
            ->filters([

            ])
            ->defaultSort('share_percent', 'desc')
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
            'index' => ListMarketAnalyses::route('/'),
            'create' => CreateMarketAnalysis::route('/create'),
            'view' => ViewMarketAnalysis::route('/{record}'),
            'edit' => EditMarketAnalysis::route('/{record}/edit'),
        ];
    }

}
