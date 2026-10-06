<?php

namespace App\Filament\Widgets;

use App\Models\AuditRequest;
use App\Models\Lead;
use App\Models\NewsletterSubscriber;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GrowthStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('leads.view') ?? false;
    }

    protected function getStats(): array
    {
        $from = now()->subDays(30);
        $prev = now()->subDays(60);

        $leads = Lead::query()->where('created_at', '>=', $from)->where('status', '!=', 'spam')->count();
        $leadsPrev = Lead::query()->whereBetween('created_at', [$prev, $from])->where('status', '!=', 'spam')->count();
        $qualified = Lead::query()->where('created_at', '>=', $from)->qualified()->count();
        $audits = AuditRequest::query()->where('created_at', '>=', $from)->count();
        $rate = $leads ? round($qualified / $leads * 100, 1) : 0;

        $trend = collect(range(13, 0))->map(fn ($d) => Lead::query()->whereDate('created_at', now()->subDays($d))->count())->all();

        return [
            Stat::make('Total leads (30 days)', number_format($leads))
                ->description($leadsPrev ? (($leads >= $leadsPrev ? '+' : '').round(($leads - $leadsPrev) / $leadsPrev * 100).'% vs previous 30 days') : 'No previous data')
                ->descriptionIcon($leads >= $leadsPrev ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($leads >= $leadsPrev ? 'success' : 'warning')
                ->chart($trend),
            Stat::make('Qualified leads', number_format($qualified))->description('Qualified, opportunity or customer'),
            Stat::make('Lead → qualified rate', $rate.'%')->description('Last 30 days'),
            Stat::make('Audit requests', number_format($audits))
                ->description(AuditRequest::query()->where('status', 'needs_review')->count().' need analyst review'),
            Stat::make('Newsletter subscribers', number_format(NewsletterSubscriber::query()->where('status', 'subscribed')->count())),
        ];
    }
}
