<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditRequestResource\Pages;
use App\Models\AuditDimension;
use App\Models\AuditRequest;
use App\Services\Audit\AuditService;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditRequestResource extends Resource
{
    protected static ?string $model = AuditRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Growth';

    protected static ?string $navigationLabel = 'Audit requests';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = AuditRequest::query()->where('status', 'needs_review')->count();

        return $count ? (string) $count : null;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make()->columns(4)->schema([
                Infolists\Components\TextEntry::make('type')->badge()->formatStateUsing(fn ($state) => AuditDimension::TYPES[$state] ?? $state),
                Infolists\Components\TextEntry::make('status')->badge()->formatStateUsing(fn ($state) => AuditRequest::STATUSES[$state] ?? $state),
                Infolists\Components\TextEntry::make('overall_score')->suffix('/100')->size('lg')->weight('bold'),
                Infolists\Components\TextEntry::make('engine')->placeholder('—'),
                Infolists\Components\TextEntry::make('company'),
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('email')->copyable(),
                Infolists\Components\TextEntry::make('website')->placeholder('—'),
                Infolists\Components\TextEntry::make('industry')->formatStateUsing(fn ($state) => config("advertally.industries.{$state}", $state))->placeholder('—'),
                Infolists\Components\TextEntry::make('country')->placeholder('—'),
                Infolists\Components\TextEntry::make('primary_service')->placeholder('—'),
                Infolists\Components\TextEntry::make('competitor')->placeholder('—'),
                Infolists\Components\TextEntry::make('summary')->columnSpanFull()->placeholder('—'),
                Infolists\Components\TextEntry::make('error')->columnSpanFull()->color('danger')->visible(fn ($record) => filled($record->error)),
            ]),
            Infolists\Components\RepeatableEntry::make('scores')->columns(3)->schema([
                Infolists\Components\TextEntry::make('dimension.name')->label('Dimension')->weight('bold'),
                Infolists\Components\TextEntry::make('score')->suffix('/100'),
                Infolists\Components\TextEntry::make('summary')->placeholder('—'),
            ]),
            Infolists\Components\RepeatableEntry::make('recommendations')->columns(4)->schema([
                Infolists\Components\TextEntry::make('title')->weight('bold')->columnSpan(1),
                Infolists\Components\TextEntry::make('impact')->badge(),
                Infolists\Components\TextEntry::make('description')->columnSpan(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('company')->weight('bold')->searchable()->description(fn ($record) => $record->website),
                Tables\Columns\TextColumn::make('type')->badge()->formatStateUsing(fn ($state) => AuditDimension::TYPES[$state] ?? $state),
                Tables\Columns\TextColumn::make('overall_score')->label('Score')->sortable()
                    ->color(fn ($state) => $state === null ? 'gray' : ($state >= 75 ? 'success' : ($state >= 50 ? 'info' : 'warning'))),
                Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn ($state) => AuditRequest::STATUSES[$state] ?? $state)
                    ->color(fn ($state) => match ($state) { 'completed' => 'success', 'needs_review' => 'warning', 'failed' => 'danger', default => 'gray' }),
                Tables\Columns\TextColumn::make('email')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options(AuditDimension::TYPES),
                Tables\Filters\SelectFilter::make('status')->options(AuditRequest::STATUSES),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('report')->icon('heroicon-o-arrow-top-right-on-square')
                    ->visible(fn (AuditRequest $record) => $record->status === 'completed')->url(fn (AuditRequest $record) => $record->url())->openUrlInNewTab(),
                Tables\Actions\Action::make('rerun')->label('Re-run')->icon('heroicon-o-arrow-path')
                    ->visible(fn (AuditRequest $record) => $record->type === 'ai_visibility' && auth()->user()->hasPermission('audits.manage'))
                    ->requiresConfirmation()
                    ->action(function (AuditRequest $record) {
                        app(AuditService::class)->runAiVisibility($record);
                        Notification::make()->title('Audit status: '.(AuditRequest::STATUSES[$record->fresh()->status] ?? $record->status))->success()->send();
                    }),
                Tables\Actions\Action::make('lead')->icon('heroicon-o-bolt')->visible(fn ($record) => $record->lead_id)
                    ->url(fn ($record) => LeadResource::getUrl('view', ['record' => $record->lead_id])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditRequests::route('/'),
            'view' => Pages\ViewAuditRequest::route('/{record}'),
        ];
    }
}
