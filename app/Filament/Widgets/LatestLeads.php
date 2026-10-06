<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestLeads extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest leads';

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('leads.view') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LeadResource::getEloquentQuery()->latest()->limit(8))
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold')->description(fn (Lead $record) => $record->company),
                Tables\Columns\TextColumn::make('form_type')->badge()->formatStateUsing(fn ($state) => Lead::FORM_TYPES[$state] ?? $state),
                Tables\Columns\TextColumn::make('source')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('score')->label('Fit'),
                Tables\Columns\TextColumn::make('status')->badge()->formatStateUsing(fn ($state) => Lead::STATUSES[$state] ?? $state),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->recordUrl(fn (Lead $record) => LeadResource::getUrl('view', ['record' => $record]));
    }
}
