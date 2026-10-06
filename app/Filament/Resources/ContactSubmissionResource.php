<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Models\ContactSubmission;
use App\Models\Lead;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Raw, unedited copy of every public form post ("Forms" in the brief).
 */
class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationGroup = 'Growth';

    protected static ?string $navigationLabel = 'Form submissions';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\TextEntry::make('form')->badge()->formatStateUsing(fn ($state) => Lead::FORM_TYPES[$state] ?? $state),
            Infolists\Components\TextEntry::make('created_at')->dateTime(),
            Infolists\Components\TextEntry::make('page_url')->columnSpanFull(),
            Infolists\Components\KeyValueEntry::make('payload')->columnSpanFull()
                ->state(fn (ContactSubmission $record) => collect($record->payload)->map(fn ($v) => is_array($v) ? json_encode($v) : (string) $v)->all()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('lead.name')->label('Lead')->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('form')->badge()->formatStateUsing(fn ($state) => Lead::FORM_TYPES[$state] ?? $state),
                Tables\Columns\TextColumn::make('page_url')->limit(50)->color('gray'),
                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([Tables\Filters\SelectFilter::make('form')->options(Lead::FORM_TYPES)])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('lead')->icon('heroicon-o-bolt')->visible(fn ($record) => $record->lead_id)
                    ->url(fn ($record) => LeadResource::getUrl('view', ['record' => $record->lead_id])),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageContactSubmissions::route('/')];
    }
}
