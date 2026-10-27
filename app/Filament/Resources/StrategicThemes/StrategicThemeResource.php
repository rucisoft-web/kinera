<?php

namespace App\Filament\Resources\StrategicThemes;

use App\Filament\Resources\StrategicThemes\Pages\ListStrategicThemes;
use App\Filament\Resources\StrategicThemes\Pages\CreateStrategicTheme;
use App\Filament\Resources\StrategicThemes\Pages\ViewStrategicTheme;
use App\Filament\Resources\StrategicThemes\Pages\EditStrategicTheme;
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

class StrategicThemeResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\StrategicTheme::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Tema Strategis';

    protected static ?string $pluralModelLabel = 'Tema Strategis';

    protected static ?string $navigationLabel = 'Tema Strategis';

    protected static ?string $navigationGroupKey = 'karma_phala';

    protected static ?string $viewPermission = 'strategy.view';

    protected static ?string $editPermission = 'strategy.edit';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('name')->label('Tema')->required()->columnSpanFull(),
                Textarea::make('description')->label('Uraian')->rows(3)->columnSpanFull(),
                TextInput::make('color')->label('Warna (hex)')->placeholder('#1F4E8C'),
                TextInput::make('sort')->label('Urutan')->numeric()->default(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                TextColumn::make('name')->label('Tema')->weight('bold')->description(fn ($record) => $record->description)->wrap(),
                TextColumn::make('objectives_count')->label('Sasaran')->counts('objectives'),
                TextColumn::make('initiatives_count')->label('Inisiatif')->counts('initiatives'),
            ])
            ->filters([

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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStrategicThemes::route('/'),
            'create' => CreateStrategicTheme::route('/create'),
            'view' => ViewStrategicTheme::route('/{record}'),
            'edit' => EditStrategicTheme::route('/{record}/edit'),
        ];
    }

}
