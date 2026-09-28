<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ClientResource;
use App\Models\Client;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

/**
 * "Clients using only Marketing → pitch Website/CRM": active clients who haven't climbed the full ladder.
 */
class UpsellOpportunities extends TableWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Upsell opportunities — next step on the growth ladder';

    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'sales'], true);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ClientResource::getEloquentQuery()
                    ->where('status', 'active')
                    ->orderByDesc('monthly_value')
            )
            ->columns([
                Tables\Columns\TextColumn::make('company')->weight('bold')->description(fn (Client $r) => $r->contact_name),
                Tables\Columns\TextColumn::make('active_pillars')->label('Using now')->badge()->color('gray')
                    ->formatStateUsing(fn ($state) => config("advertally.ladder.{$state}.label") ?? $state),
                Tables\Columns\TextColumn::make('next_pillar_label')->label('Pitch next')->badge()->color('warning')->icon('heroicon-m-arrow-up-right')->placeholder('Full ladder ✓'),
                Tables\Columns\TextColumn::make('monthly_value')->label('MRR')->money('INR'),
                Tables\Columns\TextColumn::make('accountManager.name')->label('Manager'),
            ])
            ->actions([
                Tables\Actions\Action::make('open')->label('Open')->url(fn (Client $r) => ClientResource::getUrl('edit', ['record' => $r])),
            ])
            ->paginated([5, 10]);
    }
}
