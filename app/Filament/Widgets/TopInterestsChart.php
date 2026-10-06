<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\ChartWidget;

class TopInterestsChart extends ChartWidget
{
    protected static ?string $heading = 'Top services & industries (90 days)';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '260px';

    public ?string $filter = 'service_interest';

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('leads.view') ?? false;
    }

    protected function getFilters(): ?array
    {
        return ['service_interest' => 'Services', 'industry' => 'Industries'];
    }

    protected function getData(): array
    {
        $column = $this->filter === 'industry' ? 'industry' : 'service_interest';
        $labels = config($column === 'industry' ? 'advertally.industries' : 'advertally.service_interests');

        $rows = Lead::query()->where('created_at', '>=', now()->subDays(90))->whereNotNull($column)
            ->selectRaw("{$column} as k, COUNT(*) as total")->groupBy('k')->orderByDesc('total')->limit(8)->pluck('total', 'k');

        return [
            'datasets' => [['label' => 'Leads', 'data' => $rows->values()->all(), 'backgroundColor' => '#2563EB', 'borderRadius' => 6]],
            'labels' => $rows->keys()->map(fn ($k) => str($labels[$k] ?? $k)->limit(28)->toString())->all(),
        ];
    }

    protected function getOptions(): array
    {
        return ['indexAxis' => 'y', 'plugins' => ['legend' => ['display' => false]]];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
