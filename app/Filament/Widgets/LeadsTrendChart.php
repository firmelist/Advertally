<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LeadResource;
use Filament\Widgets\ChartWidget;

class LeadsTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Leads — last 30 days';

    protected static ?int $sort = 2;


    protected static ?string $maxHeight = '260px';

    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'sales'], true);
    }

    protected function getData(): array
    {
        $rows = LeadResource::getEloquentQuery()
            ->where('created_at', '>=', today()->subDays(29))
            ->get(['created_at', 'score'])
            ->groupBy(fn ($l) => $l->created_at->toDateString());

        $days = collect(range(29, 0))->map(fn ($d) => today()->subDays($d));

        return [
            'datasets' => [
                [
                    'label' => 'All leads',
                    'data' => $days->map(fn ($d) => $rows->get($d->toDateString())?->count() ?? 0)->all(),
                    'borderColor' => '#2952CC',
                    'backgroundColor' => 'rgba(41, 82, 204, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Hot (60+)',
                    'data' => $days->map(fn ($d) => $rows->get($d->toDateString())?->where('score', '>=', 60)->count() ?? 0)->all(),
                    'borderColor' => '#F26B1D',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.35,
                ],
            ],
            'labels' => $days->map(fn ($d) => $d->format('d M'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
