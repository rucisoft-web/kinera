<?php

namespace App\Filament\Resources\StrategicInitiatives\RelationManagers;

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

class MilestonesRelationManager extends RelationManager
{
    protected static string $relationship = 'milestones';

    protected static ?string $title = 'Milestone';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('name')->label('Milestone')->required()->columnSpanFull()->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            DatePicker::make('due_date')->label('Tenggat')->required()->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            TextInput::make('weight')->label('Bobot')->numeric()->default(1)->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            Toggle::make('is_achieved')->label('Tercapai'),
            DatePicker::make('achieved_at')->label('Tanggal tercapai'),
            Textarea::make('evidence')->label('Bukti')->rows(2)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Milestone')->wrap(),
                TextColumn::make('due_date')->label('Tenggat')->date('d M Y')->sortable()->color(fn ($record) => ! $record->is_achieved && $record->due_date->isPast() ? 'danger' : null),
                IconColumn::make('is_achieved')->label('Tercapai')->boolean(),
                TextColumn::make('achieved_at')->label('Tanggal')->date('d M Y'),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make()->visible(fn () => Auth::user()?->hasPermission('initiative.edit'))->mutateDataUsing(fn (array $data) => $data + ['owner_id' => $this->getOwnerRecord()->owner_id]),
            ])
            ->recordActions([
                EditAction::make()->label('Perbarui')->visible(fn ($record) => \App\Filament\Resources\InitiativeActivities\InitiativeActivityResource::canUpdateProgress($record)),
                DeleteAction::make()->visible(fn () => Auth::user()?->hasPermission('initiative.edit')),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}
