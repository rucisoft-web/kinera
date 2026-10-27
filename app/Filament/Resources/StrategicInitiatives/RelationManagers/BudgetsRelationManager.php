<?php

namespace App\Filament\Resources\StrategicInitiatives\RelationManagers;

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

class BudgetsRelationManager extends RelationManager
{
    protected static string $relationship = 'budgets';

    protected static ?string $title = 'Anggaran inisiatif';

    protected static ?string $recordTitleAttribute = 'item';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('item')->label('Item anggaran')->required()->columnSpanFull()->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            Select::make('category')->label('Kategori')->options(['capex' => 'Capex', 'opex' => 'Opex'])->native(false)->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            TextInput::make('funding_source')->label('Sumber dana')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            F::money('planned_amount', 'Rencana')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            F::money('realized_amount', 'Realisasi'),
            DatePicker::make('realization_date')->label('Tanggal realisasi'),
            Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item')->label('Item')->wrap(),
                TextColumn::make('category')->label('Kat.')->badge(),
                TextColumn::make('planned_amount')->label('Rencana')->formatStateUsing(fn ($state) => Fmt::rp($state))->alignEnd(),
                TextColumn::make('realized_amount')->label('Realisasi')->formatStateUsing(fn ($state) => Fmt::rp($state))->alignEnd(),
                TextColumn::make('absorb')->label('Serapan')->state(fn ($record) => $record->planned_amount > 0 ? Fmt::pct($record->realized_amount / $record->planned_amount * 100, 0) : '-'),
                TextColumn::make('verifier.name')->label('Diverifikasi'),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make()->visible(fn () => Auth::user()?->hasPermission('initiative.edit')),
            ])
            ->recordActions([
                EditAction::make()->visible(fn () => Auth::user()?->hasAnyPermission(['initiative.edit', 'initiative.budget']))
                    ->mutateDataUsing(fn (array $data) => Auth::user()?->hasPermission('initiative.budget') ? $data + ['verified_by' => Auth::id(), 'verified_at' => now()] : $data),
                DeleteAction::make()->visible(fn () => Auth::user()?->hasPermission('initiative.edit')),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}
