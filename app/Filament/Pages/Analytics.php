<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LeadSourcesChart;
use App\Filament\Widgets\TopInterestsChart;
use App\Models\Lead;
use App\Services\AI\AiManager;
use Filament\Pages\Page;

/**
 * Integration status + campaign and landing page performance from lead attribution data.
 */
class Analytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationGroup = 'Growth';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.analytics';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasPermission('leads.view') ?? false;
    }

    protected function getHeaderWidgets(): array
    {
        return [LeadSourcesChart::class, TopInterestsChart::class];
    }

    protected function getViewData(): array
    {
        $since = now()->subDays(90);
        $a = config('advertally.analytics');
        $ai = app(AiManager::class);

        $group = fn (string $column) => Lead::query()->where('created_at', '>=', $since)->whereNotNull($column)
            ->selectRaw("{$column} as label, COUNT(*) as leads, SUM(CASE WHEN status IN ('qualified','opportunity','won') THEN 1 ELSE 0 END) as qualified")
            ->groupBy('label')->orderByDesc('leads')->limit(10)->get();

        return [
            'integrations' => [
                'Google Tag Manager' => $a['gtm_id'], 'GA4' => $a['ga4_id'], 'Meta Pixel' => $a['meta_pixel_id'],
                'LinkedIn Insight Tag' => $a['linkedin_partner_id'], 'Microsoft Clarity' => $a['clarity_id'],
                'Search Console verification' => $a['google_site_verification'],
                'AI provider' => $ai->enabled() ? $ai->provider()->name() : null,
            ],
            'campaigns' => $group('utm_campaign'),
            'landingPages' => $group('landing_page'),
            'firstTouch' => $group('first_touch_source'),
        ];
    }
}
