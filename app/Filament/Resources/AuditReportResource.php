<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\AuditReportResource\Pages;
use App\Models\AuditReport;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditReportResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin', 'sales'];

    protected static ?string $model = AuditReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with('lead')->when(auth()->user()?->isSales(), fn ($q) => $q->whereHas('lead', fn ($l) => $l->where('assigned_to', auth()->id()))))
            ->columns([
                Tables\Columns\TextColumn::make('url')->label('Website')->searchable()->limit(40)->weight('bold'),
                Tables\Columns\TextColumn::make('score')->badge()->color(fn ($state) => $state >= 85 ? 'success' : ($state >= 60 ? 'warning' : 'danger')),
                Tables\Columns\TextColumn::make('lead.name')->label('Lead')
                    ->url(fn (AuditReport $r) => $r->lead_id ? LeadResource::getUrl('view', ['record' => $r->lead_id]) : null),
                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')->label('Open report')->icon('heroicon-o-arrow-top-right-on-square')->url(fn (AuditReport $r) => route('audit.show', $r), true),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListAuditReports::route('/')];
    }
}
