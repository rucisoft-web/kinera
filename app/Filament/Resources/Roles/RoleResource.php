<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\ViewRole;
use App\Filament\Resources\Roles\Pages\EditRole;
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

class RoleResource extends Resource
{
    use HasBscPermissions;

    protected static ?string $model = Models\Role::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Peran';

    protected static ?string $pluralModelLabel = 'Peran';

    protected static ?string $navigationLabel = 'Peran';

    protected static ?string $navigationGroupKey = 'Administrasi';

    protected static ?string $viewPermission = 'system.admin';

    protected static ?string $editPermission = 'system.admin';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(2)->schema([
                TextInput::make('name')->label('Nama peran')->required(),
                TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true)->disabled(fn ($record) => $record?->is_system),
                Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                CheckboxList::make('permissions')->label('Izin')->relationship('permissions', 'slug')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->slug.' — '.$record->name)->columns(2)->columnSpanFull()->bulkToggleable(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Peran')->searchable()->description(fn ($record) => $record->slug),
                TextColumn::make('description')->label('Deskripsi')->wrap()->limit(90),
                TextColumn::make('permissions_count')->label('Izin')->counts('permissions'),
                IconColumn::make('is_system')->label('Sistem')->boolean(),
            ])
            ->filters([

            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([

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
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
    public static function canEdit(Model $record): bool
    {
        // Peran berlaku untuk semua instansi, jadi hanya Super Admin yang boleh mengubah isinya.
        return (bool) Auth::user()?->isSuperAdmin();
    }

    public static function canCreate(): bool
    {
        return (bool) Auth::user()?->isSuperAdmin();
    }

    public static function canDelete(Model $record): bool
    {
        return (bool) Auth::user()?->isSuperAdmin() && ! $record->is_system;
    }
}
