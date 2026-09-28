<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class FollowUpsDue extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Call these first — hot & overdue leads';

    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'sales'], true);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LeadResource::getEloquentQuery()
                    ->whereNotIn('status', ['won', 'lost'])
                    ->where(fn ($q) => $q->where('next_follow_up_at', '<=', now()->endOfDay())
                        ->orWhere(fn ($q) => $q->where('status', 'new')->where('score', '>=', 50)))
                    ->orderByRaw('next_follow_up_at is null')
                    ->orderBy('next_follow_up_at')
                    ->orderByDesc('score')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold')->description(fn (Lead $r) => $r->company),
                Tables\Columns\TextColumn::make('phone')->copyable(),
                Tables\Columns\TextColumn::make('score')->badge()->color(fn ($state) => LeadResource::scoreColor((int) $state)),
                Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn ($state) => config('advertally.lead_statuses')[$state] ?? $state)->color(fn ($state) => LeadResource::statusColor($state)),
                Tables\Columns\TextColumn::make('next_follow_up_at')->label('Due')->since()->placeholder('New — not contacted')->color('danger'),
            ])
            ->actions([
                Tables\Actions\Action::make('wa')->label('WhatsApp')->icon('heroicon-o-chat-bubble-left-right')->color('success')->url(fn (Lead $r) => $r->whatsapp_url, true),
                Tables\Actions\Action::make('open')->label('Open')->url(fn (Lead $r) => LeadResource::getUrl('view', ['record' => $r])),
            ])
            ->emptyStateHeading('All caught up 🎉')
            ->paginated([5, 10]);
    }
}
