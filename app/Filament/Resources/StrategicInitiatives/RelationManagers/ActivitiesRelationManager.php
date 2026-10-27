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

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Aktivitas';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('name')->label('Aktivitas')->required()->columnSpanFull()->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            F::user('owner_id', 'PIC aktivitas')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            DatePicker::make('start_date')->label('Mulai')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            DatePicker::make('due_date')->label('Tenggat')->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            TextInput::make('weight')->label('Bobot')->numeric()->default(1)->disabled(fn () => ! Auth::user()?->hasPermission('initiative.edit')),
            TextInput::make('progress')->label('Progres (%)')->numeric()->minValue(0)->maxValue(100)->required()->suffix('%'),
            Textarea::make('last_update_note')->label('Catatan perkembangan')->rows(2)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Aktivitas')->wrap()->description(fn ($record) => $record->last_update_note),
                TextColumn::make('owner.name')->label('PIC'),
                TextColumn::make('due_date')->label('Tenggat')->date('d M Y')->sortable(),
                TextColumn::make('progress')->label('Progres')->formatStateUsing(fn ($state) => Fmt::num($state).'%')->alignEnd(),
                F::statusColumn('status', ['belum' => 'Belum mulai', 'berjalan' => 'Berjalan', 'terlambat' => 'Terlambat', 'selesai' => 'Selesai'], ['belum' => 'gray', 'berjalan' => 'info', 'terlambat' => 'danger', 'selesai' => 'success']),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make()->visible(fn () => Auth::user()?->hasPermission('initiative.edit')),
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
