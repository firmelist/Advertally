<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Stores first-touch marketing attribution (UTM, click IDs, referrer, landing page)
 * and a page-view counter in the session, so every lead knows where it came from.
 */
class CaptureAttribution
{
    public const KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid'];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->ajax() && ! $request->is('admin*', 'livewire*', 'up')) {
            $session = $request->session();

            if (! $session->has('attribution')) {
                $referrer = (string) $request->headers->get('referer', '');
                $isInternal = $referrer && str_contains($referrer, $request->getHost());

                $session->put('attribution', array_filter([
                    ...$request->only(self::KEYS),
                    'referrer' => $isInternal ? null : mb_substr($referrer, 0, 500),
                    'landing_page' => mb_substr($request->fullUrl(), 0, 500),
                ]));
            } elseif ($request->hasAny(self::KEYS)) {
                // A new campaign click overrides UTM values but keeps the original landing page.
                $session->put('attribution', array_merge(
                    $session->get('attribution', []),
                    array_filter($request->only(self::KEYS)),
                ));
            }

            $session->put('pages_viewed', (int) $session->get('pages_viewed', 0) + 1);
        }

        return $next($request);
    }
}
