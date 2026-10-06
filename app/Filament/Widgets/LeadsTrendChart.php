<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\ChartWidget;

class LeadsTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Leads vs qualified — last 30 days';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '260px';

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('leads.view') ?? false;
    }

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn ($d) => now()->subDays($d)->startOfDay());

        $all = Lead::query()->where('created_at', '>=', $days->first())->get(['created_at', 'status'])
            ->groupBy(fn ($l) => $l->created_at->toDateString());

        return [
            'datasets' => [
                ['label' => 'Leads', 'data' => $days->map(fn ($d) => $all->get($d->toDateString())?->count() ?? 0)->all(), 'borderColor' => '#2563EB', 'backgroundColor' => 'rgba(37,99,235,.1)', 'fill' => true, 'tension' => .3],
                ['label' => 'Qualified', 'data' => $days->map(fn ($d) => $all->get($d->toDateString())?->whereIn('status', Lead::QUALIFIED)->count() ?? 0)->all(), 'borderColor' => '#16A34A', 'tension' => .3],
            ],
            'labels' => $days->map(fn ($d) => $d->format('d M'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
