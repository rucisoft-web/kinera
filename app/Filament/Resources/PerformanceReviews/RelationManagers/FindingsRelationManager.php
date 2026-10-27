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

class FindingsRelationManager extends RelationManager
{
    protected static string $relationship = 'findings';

    protected static ?string $title = 'Temuan dari tinjauan ini';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('title')->label('Temuan')->required()->columnSpanFull(),
            Textarea::make('description')->label('Uraian')->rows(3)->columnSpanFull(),
            F::perspective(),
            Select::make('severity')->label('Tingkat')->options(Models\Finding::SEVERITIES)->default('sedang')->native(false),
            F::money('amount', 'Nilai dampak (opsional)'),
            Select::make('source')->label('Sumber')->options(Models\Finding::SOURCES)->default('tinjauan')->native(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Kode')->formatStateUsing(fn ($state) => Fmt::shortCode($state)),
                TextColumn::make('title')->label('Temuan')->wrap()->url(fn ($record) => \App\Filament\Resources\Findings\FindingResource::getUrl('view', ['record' => $record])),
                TextColumn::make('severity')->label('Tingkat')->badge(),
                F::statusColumn('status', Models\Finding::STATUSES, ['terbuka' => 'danger', 'dalam_tindak_lanjut' => 'warning', 'siap_ditutup' => 'info', 'ditutup' => 'success']),
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make()->label('Buat temuan')
                     ->visible(fn () => Auth::user()?->hasAnyPermission(['finding.create', 'finding.edit']))
                     ->mutateDataUsing(fn (array $data) => $data + ['raised_by' => Auth::id(), 'raised_at' => now()->toDateString(), 'status' => 'terbuka']),
            ])
            ->recordActions([

            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}
