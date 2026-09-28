<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LeadResource;
use Filament\Widgets\ChartWidget;

class LeadsBySourceChart extends ChartWidget
{
    protected static ?string $heading = 'Lead sources (30 days)';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '260px';

    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'sales'], true);
    }

    protected function getData(): array
    {
        $data = LeadResource::getEloquentQuery()
            ->where('created_at', '>=', now()->subDays(30))
            ->get(['utm_source'])
            ->groupBy(fn ($l) => $l->utm_source ?: 'direct / organic')
            ->map->count()
            ->sortDesc()
            ->take(6);

        return [
            'datasets' => [[
                'data' => $data->values()->all(),
                'backgroundColor' => ['#2952CC', '#F26B1D', '#0F766E', '#93AFFF', '#FDA071', '#52607A'],
                'borderWidth' => 0,
            ]],
            'labels' => $data->keys()->map(fn ($k) => ucfirst($k))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return ['plugins' => ['legend' => ['position' => 'bottom']], 'scales' => ['x' => ['display' => false], 'y' => ['display' => false]]];
    }
}
