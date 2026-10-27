<?php

namespace App\Filament\Resources\AtmanProfiles\RelationManagers;

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

class CoreValuesRelationManager extends RelationManager
{
    protected static string $relationship = 'coreValues';

    protected static ?string $title = 'Nilai Inti';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('name')->label('Nilai')->required(),
            TextInput::make('local_name')->label('Ungkapan Sunda / lokal'),
            Textarea::make('description')->label('Makna')->rows(2)->columnSpanFull(),
            Textarea::make('behaviors')->label('Perilaku kunci (satu per baris)')->rows(4)->columnSpanFull(),
            TextInput::make('sort')->label('Urutan')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort')->label('#'),
                TextColumn::make('name')->label('Nilai')->weight('bold')->description(fn ($record) => $record->local_name),
                TextColumn::make('description')->label('Makna')->wrap(),
                TextColumn::make('behaviors')->label('Perilaku kunci')->wrap()->formatStateUsing(fn ($state) => str_replace("\n", ' • ', (string) $state)),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
    public function isReadOnly(): bool
    {
        return ! Auth::user()?->hasPermission('atman.edit');
    }
}
