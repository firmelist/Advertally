<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Pages\LeadPipeline;
use App\Filament\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pipeline')->label('Pipeline board')->icon('heroicon-o-view-columns')->color('gray')
                ->url(LeadPipeline::getUrl()),
            Actions\CreateAction::make()->label('Add lead'),
        ];
    }

    public function getTabs(): array
    {
        $base = LeadResource::getEloquentQuery();

        return [
            'all' => Tab::make('All'),
            'new' => Tab::make('New')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new'))
                ->badge((clone $base)->where('status', 'new')->count())->badgeColor('warning'),
            'hot' => Tab::make('Hot')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('score', '>=', 60)->whereNotIn('status', ['won', 'lost']))
                ->badge((clone $base)->where('score', '>=', 60)->whereNotIn('status', ['won', 'lost'])->count())->badgeColor('danger'),
            'follow_up' => Tab::make('Follow-ups due')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('next_follow_up_at', '<=', now())->whereNotIn('status', ['won', 'lost']))
                ->badge((clone $base)->where('next_follow_up_at', '<=', now())->whereNotIn('status', ['won', 'lost'])->count() ?: null),
            'open' => Tab::make('Open pipeline')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['contacted', 'qualified', 'proposal'])),
            'won' => Tab::make('Won')->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'won')),
        ];
    }
}
