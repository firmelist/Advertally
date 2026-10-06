<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\AiResearch;
use App\Models\CaseStudy;
use App\Models\Industry;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\User;
use App\Services\Seo;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // One SEO state object per request (and per Livewire update).
        $this->app->scoped(Seo::class);
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Super admins pass every policy check; everyone else is checked against role permissions.
        Gate::before(fn (User $user) => $user->isSuperAdmin() ? true : null);

        RateLimiter::for('leads', fn (Request $request) => [
            Limit::perMinute(5)->by($request->ip()),
            Limit::perDay(30)->by($request->ip()),
        ]);

        // The audit performs outbound HTTP requests, so keep it tighter.
        RateLimiter::for('audit', fn (Request $request) => [
            Limit::perMinute(2)->by($request->ip()),
            Limit::perDay(10)->by($request->ip()),
        ]);

        Event::listen(Login::class, function (Login $event) {
            if ($event->user instanceof User) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
                ActivityLog::record('login', $event->user, 'Signed in to admin');
            }
        });

        // Regenerate sitemap / llms.txt whenever public content changes.
        $flushDiscovery = function () {
            Cache::forget('seo.sitemap');
            Cache::forget('seo.llms');
        };

        foreach ([Page::class, ServiceCategory::class, Service::class, Industry::class, CaseStudy::class, Post::class, AiResearch::class, Setting::class] as $model) {
            $model::saved($flushDiscovery);
            $model::deleted($flushDiscovery);
        }

        View::composer(['layouts.app', 'partials.header', 'partials.footer'], function ($view) {
            try {
                $ready = Schema::hasTable('navigation_items');
            } catch (Throwable) {
                $ready = false;
            }

            $view->with([
                'headerNav' => $ready ? NavigationItem::tree('header') : collect(),
                'footerNav' => $ready ? NavigationItem::tree('footer') : collect(),
                'legalNav' => $ready ? NavigationItem::tree('legal') : collect(),
            ]);
        });
    }
}
