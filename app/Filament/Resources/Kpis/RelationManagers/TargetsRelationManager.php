<?php

namespace App\Filament\Resources\Kpis\RelationManagers;

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

class TargetsRelationManager extends RelationManager
{
    protected static string $relationship = 'targets';

    protected static ?string $title = 'Target bulanan';

    protected static ?string $recordTitleAttribute = 'month';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Select::make('month')->label('Bulan')->options(Terms::months())->required()->native(false),
            TextInput::make('target')->label('Target')->numeric()->required(),
            Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('month')->label('Bulan')->formatStateUsing(fn ($state) => Terms::months()[$state] ?? $state)->sortable(),
                TextColumn::make('target')->label('Target')->formatStateUsing(fn ($state, $record) => Fmt::kpiValue($state, $record->kpi?->unit, $record->kpi?->decimals ?? 0))->alignEnd(),
                TextColumn::make('notes')->label('Catatan')->limit(50),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make()->visible(fn () => Auth::user()?->hasPermission('kpi.edit')),
            ])
            ->recordActions([
                EditAction::make()->visible(fn () => Auth::user()?->hasPermission('kpi.edit')),
                DeleteAction::make()->visible(fn () => Auth::user()?->hasPermission('kpi.edit')),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}
