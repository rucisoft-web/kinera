<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Concerns\HasBscPermissions;
use App\Filament\Concerns\ScopesToActiveCompany;
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

class UserResource extends Resource
{
    use HasBscPermissions, ScopesToActiveCompany {
        HasBscPermissions::canEdit as canEditByPermission;
        HasBscPermissions::canDelete as canDeleteByPermission;
    }

    protected static ?string $model = Models\User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $navigationGroupKey = 'Administrasi';

    protected static ?string $viewPermission = 'system.admin';

    protected static ?string $editPermission = 'system.admin';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Akun')->columns(2)->schema([
                TextInput::make('name')->label('Nama')->required(),
                TextInput::make('email')->label('Email')->email()->required()->unique(ignoreRecord: true),
                TextInput::make('job_title')->label('Jabatan'),
                TextInput::make('department')->label('Departemen'),
                TextInput::make('phone')->label('Telepon / WA'),
                Toggle::make('is_active')->label('Aktif')->default(true),
                TextInput::make('password')->label('Kata sandi awal')->password()->revealable()
                    ->rule(\Illuminate\Validation\Rules\Password::defaults())
                    ->required(fn (string $operation) => $operation === 'create')->dehydrated(fn ($state) => filled($state))
                    ->helperText('Minimal 10 karakter, huruf & angka. Pengguna wajib mengganti saat login pertama.')->visibleOn('create'),
                Toggle::make('must_change_password')->label('Wajib ganti kata sandi saat login')->default(true),
                // Hanya Super Admin yang mengatur akses lintas satker BLU; admin lain otomatis memakai satker BLU aktif (CreateUser).
                Select::make('companies')->label('Akses satker BLU')->relationship('companies', 'name')->multiple()->preload()->columnSpanFull()
                    ->visible(fn () => static::managesAllCompanies()),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->description(fn ($record) => $record->job_title),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('roles.name')->label('Peran')->badge()->separator(','),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                IconColumn::make('must_change_password')->label('Wajib ganti sandi')->boolean()->toggleable(),
                TextColumn::make('last_login_at')->label('Login terakhir')->since()->placeholder('belum pernah'),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                Action::make('reset')->label('Reset sandi')->icon('heroicon-o-key')->color('warning')->visible(fn ($record) => static::canEdit($record))
                    ->form([TextInput::make('password')->label('Kata sandi sementara')->password()->revealable()->required()->rule(\Illuminate\Validation\Rules\Password::defaults())])
                    ->action(function ($record, array $data) { $record->update(['password' => $data['password'], 'must_change_password' => true]); Notification::make()->title('Kata sandi direset; pengguna wajib menggantinya saat login')->success()->send(); }),
                EditAction::make(),
            ])
            ->toolbarActions([

            ]);
    }

    /** Admin non-Super Admin hanya melihat pengguna satker BLU yang sedang aktif. */
    public static function getEloquentQuery(): Builder
    {
        $q = parent::getEloquentQuery();
        if (static::managesAllCompanies()) {
            return $q;
        }
        $companyId = static::activeCompanyId();

        return $q->where(fn (Builder $w) => $w
            ->whereHas('companies', fn (Builder $c) => $c->whereKey($companyId))
            ->orWhereHas('roleAssignments', fn (Builder $r) => $r->where('company_id', $companyId)));
    }

    /** Akun yang juga dipakai di satker BLU lain (atau Super Admin) hanya bisa diubah oleh Super Admin. */
    public static function canEdit(Model $record): bool
    {
        return static::canEditByPermission($record) && static::ownsUserAccount($record);
    }

    public static function canDelete(Model $record): bool
    {
        return static::canDeleteByPermission($record) && static::ownsUserAccount($record) && $record->getKey() !== Auth::id();
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RoleAssignmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

}
