<?php

namespace App\Services\Audit;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Throwable;

/**
 * Fetches public web pages for audits with SSRF protection: private, reserved and internal hosts are refused,
 * including on redirects.
 */
class SafeFetcher
{
    private const UA = 'Mozilla/5.0 (compatible; AdvertallyAuditBot/2.0; +https://advertally.com/ai-visibility-audit)';

    public function normalise(string $url): string
    {
        $url = trim($url);
        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://'.$url;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        if (! $host || ! str_contains($host, '.') || isset($parts['user']) || isset($parts['port'])) {
            throw new InvalidArgumentException('Please enter a valid public website address, e.g. yourcompany.com');
        }

        $this->assertPublicHost($host);

        return 'https://'.$host.'/';
    }

    public function assertPublicHost(string $host): void
    {
        if ($host === 'localhost' || str_ends_with($host, '.local') || str_ends_with($host, '.internal')) {
            throw new InvalidArgumentException('Please enter a public website address.');
        }

        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : (gethostbynamel($host) ?: []);

        if (! $ips) {
            throw new InvalidArgumentException('We could not find that website. Please check the address.');
        }

        foreach ($ips as $ip) {
            if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new InvalidArgumentException('Please enter a public website address.');
            }
        }
    }

    public function get(string $url, int $timeout = 12): ?Response
    {
        try {
            $this->assertPublicHost((string) parse_url($url, PHP_URL_HOST));

            return Http::withHeaders(['User-Agent' => self::UA, 'Accept' => 'text/html,application/xhtml+xml,text/plain,*/*'])
                ->timeout($timeout)
                ->connectTimeout(6)
                ->withOptions(['allow_redirects' => [
                    'max' => 4,
                    'protocols' => ['http', 'https'],
                    'on_redirect' => fn ($request, $response, $uri) => $this->assertPublicHost($uri->getHost()),
                ]])
                ->get($url);
        } catch (Throwable) {
            return null;
        }
    }
}
