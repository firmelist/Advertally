<?php

namespace App\Services;

use Illuminate\Http\Request;

/**
 * Builds and reads marketing "touches": UTM parameters, click IDs, referrer, landing page and a derived channel.
 */
class Attribution
{
    public const COOKIE = 'adv_ft';

    public const SESSION_KEY = 'attribution.last';

    public const UTM = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];

    public const CLICK_IDS = ['gclid', 'fbclid', 'li_fat_id'];

    public function touchFrom(Request $request): array
    {
        $referrer = (string) $request->headers->get('referer', '');
        $refHost = strtolower((string) parse_url($referrer, PHP_URL_HOST));
        $external = $refHost !== '' && $refHost !== strtolower($request->getHost());

        $touch = array_filter([
            ...array_map(fn ($v) => mb_substr((string) $v, 0, 150), $request->only([...self::UTM, ...self::CLICK_IDS])),
            'referrer' => $external ? mb_substr($referrer, 0, 500) : null,
            'landing_page' => mb_substr($request->fullUrl(), 0, 500),
            'at' => now()->toIso8601String(),
        ]);

        $touch['source'] = $this->channel($touch, $external ? $refHost : null);

        return $touch;
    }

    /** A touch that should overwrite last-touch attribution: a tagged campaign, an ad click or an external referral. */
    public function isCampaignTouch(array $touch): bool
    {
        return isset($touch['utm_source']) || isset($touch['gclid']) || isset($touch['fbclid'])
            || isset($touch['li_fat_id']) || isset($touch['referrer']);
    }

    public function firstTouch(Request $request): array
    {
        $raw = $request->cookie(self::COOKIE);
        $decoded = is_string($raw) ? json_decode($raw, true) : null;

        return is_array($decoded) ? $decoded : $this->lastTouch($request);
    }

    public function lastTouch(Request $request): array
    {
        return $request->hasSession() ? (array) $request->session()->get(self::SESSION_KEY, []) : [];
    }

    /** Human-readable channel: "google-ads", "linkedin", "organic:google", "referral:example.com", "direct". */
    private function channel(array $touch, ?string $refHost): string
    {
        if (isset($touch['gclid'])) {
            return 'google-ads';
        }
        if (isset($touch['li_fat_id'])) {
            return 'linkedin-ads';
        }
        if (isset($touch['fbclid'])) {
            return 'meta';
        }
        if (isset($touch['utm_source'])) {
            return strtolower($touch['utm_source']);
        }
        if (! $refHost) {
            return 'direct';
        }

        $host = preg_replace('/^www\./', '', $refHost);

        foreach (['google' => 'organic:google', 'bing' => 'organic:bing', 'duckduckgo' => 'organic:duckduckgo',
            'chatgpt' => 'ai:chatgpt', 'openai' => 'ai:chatgpt', 'perplexity' => 'ai:perplexity',
            'gemini' => 'ai:gemini', 'claude' => 'ai:claude', 'copilot' => 'ai:copilot',
            'linkedin' => 'social:linkedin', 'facebook' => 'social:facebook', 'instagram' => 'social:instagram',
            'youtube' => 'social:youtube', 't.co' => 'social:x', 'x.com' => 'social:x'] as $needle => $channel) {
            if (str_contains($host, $needle)) {
                return $channel;
            }
        }

        return 'referral:'.$host;
    }
}
