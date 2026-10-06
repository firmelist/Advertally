<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\ChartWidget;

class LeadSourcesChart extends ChartWidget
{
    protected static ?string $heading = 'Lead sources (90 days)';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '260px';

    public ?string $filter = 'last';

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('leads.view') ?? false;
    }

    protected function getFilters(): ?array
    {
        return ['last' => 'Last touch', 'first' => 'First touch'];
    }

    protected function getData(): array
    {
        $column = $this->filter === 'first' ? 'first_touch_source' : 'source';

        $rows = Lead::query()->where('created_at', '>=', now()->subDays(90))
            ->selectRaw("COALESCE({$column}, 'direct') as channel, COUNT(*) as total")
            ->groupBy('channel')->orderByDesc('total')->limit(8)->pluck('total', 'channel');

        return [
            'datasets' => [[
                'data' => $rows->values()->all(),
                'backgroundColor' => ['#0B1F3A', '#2563EB', '#7C3AED', '#06B6D4', '#16A34A', '#60A5FA', '#A78BFA', '#94A3B8'],
            ]],
            'labels' => $rows->keys()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
