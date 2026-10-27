<?php

namespace App\Filament\Resources\MaturityAssessments\RelationManagers;

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

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    protected static ?string $title = 'Jawaban kuesioner (skala 1–5)';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Placeholder::make('q')->label('Pertanyaan')->content(fn ($record) => $record?->question?->question)->columnSpanFull(),
            Select::make('score')->label('Skor')->options([1 => '1 — belum ada', 2 => '2 — ada, tidak rutin', 3 => '3 — terdokumentasi & berjalan', 4 => '4 — terukur & dievaluasi', 5 => '5 — optimal & membudaya'])->required()->native(false),
            Textarea::make('evidence')->label('Bukti')->rows(2)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question.phase')->label('Fase')->formatStateUsing(fn ($state) => Terms::get($state))->badge(),
                TextColumn::make('question.dimension')->label('Dimensi'),
                TextColumn::make('question.question')->label('Pertanyaan')->wrap(),
                TextColumn::make('score')->label('Skor')->badge()->color(fn ($state) => $state >= 4 ? 'success' : ($state >= 3 ? 'warning' : 'danger')),
                TextColumn::make('evidence')->label('Bukti')->limit(40)->toggleable(),
            ])
            ->filters([

            ])
            ->headerActions([

            ])
            ->recordActions([
                EditAction::make()->label('Nilai')->visible(fn () => Auth::user()?->hasPermission('maturity.assess'))->after(fn () => app(\App\Services\MaturityScoringService::class)->recalculate($this->getOwnerRecord())),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}
