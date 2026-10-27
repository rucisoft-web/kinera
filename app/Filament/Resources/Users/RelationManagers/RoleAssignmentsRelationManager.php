<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Concerns\ScopesToActiveCompany;
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
use Filament\Resources\RelationManagers\RelationManager;
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

class RoleAssignmentsRelationManager extends RelationManager
{
    use ScopesToActiveCompany;

    protected static string $relationship = 'roleAssignments';

    protected static ?string $title = 'Penugasan peran';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Select::make('role_id')->label('Peran')->options(fn () => static::assignableRoleOptions())->required()->native(false),
            Select::make('company_id')->label('Satker BLU')->options(fn () => static::companyOptions())
                ->placeholder('Global (semua satker BLU)')->native(false)
                ->default(fn () => static::managesAllCompanies() ? null : static::activeCompanyId())
                ->required(fn () => ! static::managesAllCompanies()),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => static::managesAllCompanies() ? $query : $query->where('company_id', static::activeCompanyId()))
            ->columns([
                TextColumn::make('role.name')->label('Peran')->description(fn ($record) => $record->role?->description)->wrap(),
                TextColumn::make('company.code')->label('Satker BLU')->placeholder('Global'),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                DeleteAction::make(),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}
