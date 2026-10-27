<?php

namespace App\Filament\Resources\StrategicChoices;

use App\Filament\Resources\StrategicChoices\Pages\ListStrategicChoices;
use App\Filament\Resources\StrategicChoices\Pages\CreateStrategicChoice;
use App\Filament\Resources\StrategicChoices\Pages\ViewStrategicChoice;
use App\Filament\Resources\StrategicChoices\Pages\EditStrategicChoice;
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

class StrategicChoiceResource extends Resource
{
    use HasDhastramPermissions;

    protected static ?string $model = Models\StrategicChoice::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-pointing-out';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Pilihan Strategis';

    protected static ?string $pluralModelLabel = 'Pilihan Strategis';

    protected static ?string $navigationLabel = 'Pilihan Strategis';

    protected static ?string $navigationGroupKey = 'karma_phala';

    protected static ?string $viewPermission = 'strategy.view';

    protected static ?string $editPermission = 'strategy.edit';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('title')->label('Pilihan strategis')->required()->columnSpanFull(),
                Select::make('strategic_theme_id')->label('Tema')->relationship('theme', 'name')->native(false),
                Select::make('choice_type')->label('Jenis')->options(['pasar' => 'Pasar', 'produk' => 'Produk', 'harga' => 'Harga', 'saluran' => 'Saluran', 'operasi' => 'Operasi', 'organisasi' => 'Organisasi'])->native(false),
                Textarea::make('description')->label('Uraian')->rows(3)->columnSpanFull(),
                Textarea::make('rationale')->label('Alasan / bukti')->rows(3)->columnSpanFull(),
                Select::make('status')->options(['diusulkan' => 'Diusulkan', 'dipilih' => 'Dipilih', 'ditolak' => 'Ditolak'])->native(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                F::code(),
                TextColumn::make('title')->label('Pilihan')->wrap()->searchable(),
                TextColumn::make('theme.name')->label('Tema')->wrap(),
                TextColumn::make('choice_type')->label('Jenis')->badge(),
                F::statusColumn('status', ['diusulkan' => 'Diusulkan', 'dipilih' => 'Dipilih', 'ditolak' => 'Ditolak'], ['diusulkan' => 'warning', 'dipilih' => 'success', 'ditolak' => 'gray']),
            ])
            ->filters([

            ])
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
            'index' => ListStrategicChoices::route('/'),
            'create' => CreateStrategicChoice::route('/create'),
            'view' => ViewStrategicChoice::route('/{record}'),
            'edit' => EditStrategicChoice::route('/{record}/edit'),
        ];
    }

}
