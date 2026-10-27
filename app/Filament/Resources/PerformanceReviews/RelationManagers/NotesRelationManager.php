<?php

namespace App\Filament\Resources\PerformanceReviews\RelationManagers;

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

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $title = 'Catatan pengawasan';

    protected static ?string $recordTitleAttribute = 'note';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Textarea::make('note')->label('Catatan pengawasan')->required()->rows(4)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y H:i'),
                TextColumn::make('user.name')->label('Oleh'),
                TextColumn::make('note')->label('Catatan')->wrap(),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make()->label('Tambah catatan')
                     ->visible(fn () => Auth::user()?->hasAnyPermission(['review.note', 'review.edit']))
                     ->mutateDataUsing(fn (array $data) => $data + ['user_id' => Auth::id()]),
            ])
            ->recordActions([
                DeleteAction::make()->visible(fn () => Auth::user()?->managesAllData()),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}
