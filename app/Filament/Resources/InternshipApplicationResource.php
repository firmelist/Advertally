<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InternshipApplicationResource\Pages;
use App\Models\InternshipApplication;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class InternshipApplicationResource extends Resource
{
    protected static ?string $model = InternshipApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationGroup = 'Careers';

    protected static ?string $navigationLabel = 'Applications';

    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = InternshipApplication::query()->where('status', 'new')->count();

        return $count ? (string) $count : null;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make()->columns(3)->schema([
                Infolists\Components\TextEntry::make('internship.title')->label('Internship')->weight('bold'),
                Infolists\Components\TextEntry::make('status')->badge()->formatStateUsing(fn ($state) => InternshipApplication::STATUSES[$state] ?? $state),
                Infolists\Components\TextEntry::make('created_at')->label('Applied')->dateTime(),
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('email')->copyable(),
                Infolists\Components\TextEntry::make('phone')->placeholder('—'),
                Infolists\Components\TextEntry::make('city')->placeholder('—'),
                Infolists\Components\TextEntry::make('education')->label('College / course')->placeholder('—'),
                Infolists\Components\TextEntry::make('graduation_year')->placeholder('—'),
                Infolists\Components\TextEntry::make('availability')->placeholder('—'),
                Infolists\Components\TextEntry::make('linkedin_url')->label('LinkedIn')->url(fn ($state) => $state, true)->placeholder('—'),
                Infolists\Components\TextEntry::make('portfolio_url')->label('Portfolio')->url(fn ($state) => $state, true)->placeholder('—'),
                Infolists\Components\TextEntry::make('motivation')->label('Why this internship')->columnSpanFull(),
                Infolists\Components\TextEntry::make('notes')->label('Internal notes')->columnSpanFull()->placeholder('—'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold')->searchable()->description(fn (InternshipApplication $record) => $record->email),
                Tables\Columns\TextColumn::make('internship.title')->label('Internship')->badge(),
                Tables\Columns\TextColumn::make('education')->label('College / course')->limit(30)->toggleable(),
                Tables\Columns\SelectColumn::make('status')->options(InternshipApplication::STATUSES)->selectablePlaceholder(false),
                Tables\Columns\TextColumn::make('created_at')->label('Applied')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('internship_id')->label('Internship')->relationship('internship', 'title'),
                Tables\Filters\SelectFilter::make('status')->options(InternshipApplication::STATUSES),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('resume')->label('Résumé')->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (InternshipApplication $record) => $record->resume_path && Storage::disk('local')->exists($record->resume_path))
                    ->action(fn (InternshipApplication $record) => Storage::disk('local')->download($record->resume_path, $record->resume_name ?: 'resume')),
                Tables\Actions\Action::make('notes')->icon('heroicon-o-pencil-square')
                    ->form([Forms\Components\Textarea::make('notes')->rows(4)])
                    ->fillForm(fn (InternshipApplication $record) => ['notes' => $record->notes])
                    ->action(fn (InternshipApplication $record, array $data) => $record->update($data)),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternshipApplications::route('/'),
            'view' => Pages\ViewInternshipApplication::route('/{record}'),
        ];
    }
}
