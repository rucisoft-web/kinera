<?php

namespace App\Filament\Resources\RoleAssignments;

use App\Filament\Resources\RoleAssignments\Pages\ListRoleAssignments;
use App\Filament\Resources\RoleAssignments\Pages\CreateRoleAssignment;
use App\Filament\Resources\RoleAssignments\Pages\EditRoleAssignment;
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

class RoleAssignmentResource extends Resource
{
    use HasBscPermissions, ScopesToActiveCompany;

    protected static ?string $model = Models\RoleAssignment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Penugasan Peran';

    protected static ?string $pluralModelLabel = 'Penugasan Peran';

    protected static ?string $navigationLabel = 'Penugasan Peran';

    protected static ?string $navigationGroupKey = 'Administrasi';

    protected static ?string $viewPermission = 'system.admin';

    protected static ?string $editPermission = 'system.admin';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make()->columns(3)->schema([
                Select::make('user_id')->label('Pengguna')->relationship('user', 'name', fn (Builder $query) => static::managesAllCompanies() ? $query : $query->where(fn (Builder $w) => $w->whereHas('companies', fn (Builder $c) => $c->whereKey(static::activeCompanyId()))->orWhereHas('roleAssignments', fn (Builder $r) => $r->where('company_id', static::activeCompanyId()))))->required()->searchable()->preload(),
                Select::make('role_id')->label('Peran')->options(fn () => static::assignableRoleOptions())->required()->native(false),
                Select::make('company_id')->label('Instansi')->options(fn () => static::companyOptions())->placeholder('Global')->native(false)
                    ->default(fn () => static::managesAllCompanies() ? null : static::activeCompanyId())
                    ->required(fn () => ! static::managesAllCompanies()),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Pengguna')->searchable()->description(fn ($record) => $record->user?->job_title),
                TextColumn::make('role.name')->label('Peran')->badge(),
                TextColumn::make('company.code')->label('Instansi')->placeholder('Global'),
            ])
            ->filters([
                SelectFilter::make('role_id')->label('Peran')->relationship('role', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /** Admin non-Super Admin hanya melihat & mengubah penugasan di instansi aktif. */
    public static function getEloquentQuery(): Builder
    {
        $q = parent::getEloquentQuery();

        return static::managesAllCompanies() ? $q : $q->where('company_id', static::activeCompanyId());
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoleAssignments::route('/'),
            'create' => CreateRoleAssignment::route('/create'),
            'edit' => EditRoleAssignment::route('/{record}/edit'),
        ];
    }

}
