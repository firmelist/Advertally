<?php

namespace App\Providers;

use App\Models\Service;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Form spam protection: 5 lead submissions / minute and 20 / day per IP.
        RateLimiter::for('leads', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip()),
                Limit::perDay(20)->by($request->ip()),
            ];
        });

        // The audit tool performs outbound HTTP calls, so keep it tighter.
        RateLimiter::for('audit', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perDay(10)->by($request->ip()),
            ];
        });

        // Share the mega-menu tree with the public layout (cached for 1 hour, cleared on Service save).
        View::composer(['layouts.app', 'partials.*'], function ($view) {
            if (! Schema::hasTable('services')) {
                $view->with('menuHubs', collect());

                return;
            }

            $view->with('menuHubs', Cache::remember('menu.hubs', 3600, fn () => Service::query()
                ->hubs()
                ->active()
                ->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')])
                ->orderBy('sort_order')
                ->get()));
        });
    }
}
