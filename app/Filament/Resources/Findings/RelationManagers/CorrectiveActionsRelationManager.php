<?php

namespace App\Filament\Resources\Findings\RelationManagers;

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

class CorrectiveActionsRelationManager extends RelationManager
{
    protected static string $relationship = 'correctiveActions';

    protected static ?string $title = 'Tindakan korektif';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('title')->label('Tindakan korektif')->required()->columnSpanFull()->disabled(fn () => ! Auth::user()?->managesAllData() && ! Auth::user()?->hasPermission('finding.create')),
            F::user('owner_id', 'PIC')->required()->disabled(fn () => ! Auth::user()?->managesAllData()),
            DatePicker::make('due_date')->label('Tenggat')->disabled(fn () => ! Auth::user()?->managesAllData()),
            Select::make('status')->options(['belum' => 'Belum', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'])->default('belum')->native(false),
            TextInput::make('progress')->label('Progres (%)')->numeric()->minValue(0)->maxValue(100)->default(0),
            Textarea::make('description')->label('Uraian')->rows(2)->columnSpanFull(),
            Textarea::make('result_note')->label('Hasil / bukti')->rows(2)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Tindakan')->wrap()->description(fn ($record) => $record->result_note),
                TextColumn::make('owner.name')->label('PIC'),
                TextColumn::make('due_date')->label('Tenggat')->date('d M Y')->color(fn ($record) => $record->is_overdue ? 'danger' : null),
                TextColumn::make('progress')->label('Progres')->formatStateUsing(fn ($state) => $state.'%'),
                F::statusColumn('status', ['belum' => 'Belum', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'], ['belum' => 'gray', 'berjalan' => 'warning', 'selesai' => 'success']),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make()->visible(fn () => $this->getOwnerRecord()->status !== 'ditutup' && (Auth::user()?->managesAllData() || Auth::user()?->hasPermission('finding.create'))),
            ])
            ->recordActions([
                EditAction::make()->label('Perbarui')->visible(fn ($record) => \App\Filament\Resources\CorrectiveActions\CorrectiveActionResource::canEdit($record)),
                DeleteAction::make()->visible(fn () => Auth::user()?->managesAllData()),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}
