<?php

namespace App\Http\Middleware;

use App\Services\Attribution;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * First-touch attribution lives in a 90-day cookie (survives sessions);
 * last-touch lives in the session and is replaced by every new campaign click or external referral.
 */
class CaptureAttribution
{
    public function __construct(private Attribution $attribution) {}

    public function handle(Request $request, Closure $next): Response
    {
        $tracked = $request->isMethod('GET')
            && ! $request->ajax()
            && ! $request->is('admin*', 'livewire*', 'up', 'sitemap.xml', 'robots.txt', 'llms.txt', 'build/*', 'storage/*');

        $touch = $tracked ? $this->attribution->touchFrom($request) : null;

        if ($touch && $this->attribution->isCampaignTouch($touch)) {
            $request->session()->put(Attribution::SESSION_KEY, $touch);
        } elseif ($touch && ! $request->session()->has(Attribution::SESSION_KEY)) {
            $request->session()->put(Attribution::SESSION_KEY, $touch);
        }

        $response = $next($request);

        if ($touch && ! $request->cookies->has(Attribution::COOKIE)) {
            $response->headers->setCookie(cookie(Attribution::COOKIE, json_encode($touch), 60 * 24 * 90, null, null, null, true, false, 'Lax'));
        }

        return $response;
    }
}
