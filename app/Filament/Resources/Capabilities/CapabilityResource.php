<?php

namespace App\Filament\Resources\Capabilities;

use App\Filament\Resources\Capabilities\Pages\ListCapabilities;
use App\Filament\Resources\Capabilities\Pages\CreateCapability;
use App\Filament\Resources\Capabilities\Pages\ViewCapability;
use App\Filament\Resources\Capabilities\Pages\EditCapability;
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

class CapabilityResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\Capability::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Kapabilitas';

    protected static ?string $pluralModelLabel = 'Kapabilitas Organisasi';

    protected static ?string $navigationLabel = 'Kapabilitas Organisasi';

    protected static ?string $navigationGroupKey = 'atman';

    protected static ?string $viewPermission = 'atman.view';

    protected static ?string $editPermission = 'atman.edit';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('name')->label('Kapabilitas')->required()->columnSpanFull(),
                Select::make('category')->label('Kategori')->options(['Sumber daya fisik' => 'Sumber daya fisik', 'Kompetensi' => 'Kompetensi', 'Sistem' => 'Sistem'])->native(false),
                Toggle::make('is_core')->label('Kapabilitas inti'),
                F::scale('current_level', 'Tingkat saat ini'),
                F::scale('target_level', 'Tingkat sasaran'),
                Textarea::make('description')->label('Uraian')->rows(3)->columnSpanFull(),
                Textarea::make('gap_action')->label('Aksi menutup kesenjangan')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Kapabilitas')->searchable()->wrap()->weight(fn ($record) => $record->is_core ? 'bold' : null),
                TextColumn::make('category')->label('Kategori')->badge(),
                IconColumn::make('is_core')->label('Inti')->boolean(),
                TextColumn::make('current_level')->label('Saat ini')->alignCenter(),
                TextColumn::make('target_level')->label('Sasaran')->alignCenter(),
                TextColumn::make('gap')->label('Kesenjangan')->state(fn ($record) => $record->target_level - $record->current_level)->badge()->color(fn ($state) => $state >= 2 ? 'danger' : ($state == 1 ? 'warning' : 'success')),
                TextColumn::make('gap_action')->label('Aksi')->limit(60)->wrap()->toggleable(),
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
            'index' => ListCapabilities::route('/'),
            'create' => CreateCapability::route('/create'),
            'view' => ViewCapability::route('/{record}'),
            'edit' => EditCapability::route('/{record}/edit'),
        ];
    }

}
