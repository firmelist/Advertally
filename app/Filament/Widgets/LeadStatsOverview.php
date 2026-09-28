<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LeadResource;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'sales'], true);
    }

    protected function getStats(): array
    {
        $q = fn () => LeadResource::getEloquentQuery();

        $today = $q()->whereDate('created_at', today())->count();
        $week = $q()->where('created_at', '>=', now()->startOfWeek())->count();
        $month = $q()->where('created_at', '>=', now()->startOfMonth())->count();
        $lastMonth = $q()->whereBetween('created_at', [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()])->count();

        $last30 = $q()->where('created_at', '>=', now()->subDays(30));
        $total30 = (clone $last30)->count();
        $won30 = (clone $last30)->where('status', 'won')->count();
        $conversion = $total30 ? round($won30 / $total30 * 100, 1) : 0;

        $pipeline = $q()->whereIn('status', ['qualified', 'proposal'])->sum('deal_value');
        $due = $q()->where('next_follow_up_at', '<=', now())->whereNotIn('status', ['won', 'lost'])->count();
        $uncontacted = $q()->where('status', 'new')->where('created_at', '<=', now()->subHours(2))->count();

        $spark = collect(range(13, 0))->map(fn ($d) => $q()->whereDate('created_at', today()->subDays($d))->count())->all();

        return [
            Stat::make('Leads today', $today)->description("{$week} this week")->descriptionIcon('heroicon-m-bolt')->chart($spark)->color('primary'),
            Stat::make('Leads this month', $month)
                ->description($lastMonth ? (($month >= $lastMonth ? '+' : '').round(($month - $lastMonth) / max($lastMonth, 1) * 100).'% vs last month') : 'First month of data')
                ->descriptionIcon($month >= $lastMonth ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($month >= $lastMonth ? 'success' : 'danger'),
            Stat::make('Win rate (30 days)', $conversion.'%')->description("{$won30} won of {$total30}")->color('success'),
            Stat::make('Open pipeline', '₹'.number_format($pipeline))->description('Qualified + proposal stage')->color('primary'),
            Stat::make('Follow-ups due', $due)->description($uncontacted.' new leads waiting 2h+')->descriptionIcon('heroicon-m-clock')
                ->color($due || $uncontacted ? 'danger' : 'gray')
                ->url(LeadResource::getUrl('index', ['activeTab' => 'follow_up'])),
        ];
    }
}
