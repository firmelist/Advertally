<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Throwable;

/**
 * Lightweight website audit used by the free lead-magnet tool.
 * Returns a list of checks (label, category, pass/warn/fail, detail, weight) and a 0–100 score.
 */
class WebsiteAuditor
{
    private const UA = 'Mozilla/5.0 (compatible; AdvertallyAuditBot/1.0; +https://advertally.com/free-website-audit)';

    /**
     * Normalise and validate a URL, blocking private / internal addresses (SSRF protection).
     */
    public function normalise(string $url): string
    {
        $url = trim($url);
        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://'.$url;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        if (! $host || ! str_contains($host, '.') || isset($parts['user']) || isset($parts['port'])) {
            throw new InvalidArgumentException('Please enter a valid public website address, e.g. yourbusiness.com');
        }

        $this->assertPublicHost($host);

        return strtolower($parts['scheme']).'://'.$host.($parts['path'] ?? '/');
    }

    public function assertPublicHost(string $host): void
    {
        if (in_array($host, ['localhost'], true) || str_ends_with($host, '.local') || str_ends_with($host, '.internal')) {
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

    /**
     * @return array{score:int, performance_score:?int, checks:array<int, array>}
     */
    public function run(string $url): array
    {
        $checks = [];
        $host = parse_url($url, PHP_URL_HOST);
        $httpsUrl = 'https://'.$host.(parse_url($url, PHP_URL_PATH) ?: '/');

        $start = microtime(true);
        $response = $this->fetch($httpsUrl);
        $elapsed = round(microtime(true) - $start, 2);

        $https = $response?->successful() ?? false;
        if (! $https) {
            $response = $this->fetch('http://'.$host.(parse_url($url, PHP_URL_PATH) ?: '/'));
            $elapsed = round(microtime(true) - $start, 2);
        }

        if (! $response || ! $response->successful()) {
            throw new InvalidArgumentException('We could not open that website (it may be down or blocking visitors). Please check the address.');
        }

        $html = (string) $response->body();
        $bytes = strlen($html);
        $xpath = $this->xpath($html);

        // --- Security --------------------------------------------------------
        $checks[] = $this->check('security', 'SSL certificate (HTTPS)', $https ? 'pass' : 'fail', 15,
            $https ? 'Your site loads securely over HTTPS.' : 'Your site does not load over HTTPS. Browsers show a "Not secure" warning, which scares customers away and hurts Google rankings.');

        // --- Speed ------------------------------------------------------------
        $checks[] = $this->check('speed', 'Server response time', $elapsed <= 1.5 ? 'pass' : ($elapsed <= 3 ? 'warn' : 'fail'), 10,
            "Your homepage responded in {$elapsed}s. ".($elapsed <= 1.5 ? 'Great.' : 'Aim for under 1.5 seconds — slow pages lose visitors, especially on mobile data.'));

        $kb = (int) round($bytes / 1024);
        $checks[] = $this->check('speed', 'HTML page size', $kb <= 150 ? 'pass' : ($kb <= 400 ? 'warn' : 'fail'), 5,
            "HTML weight is {$kb} KB. ".($kb <= 150 ? 'Nice and lean.' : 'Heavy pages load slowly — consider cleaning up page builders and inline code.'));

        // --- Mobile -----------------------------------------------------------
        $viewport = $xpath?->query('//meta[@name="viewport"]')->length > 0;
        $checks[] = $this->check('mobile', 'Mobile-friendly viewport', $viewport ? 'pass' : 'fail', 10,
            $viewport ? 'The page is set up to scale on mobile screens.' : 'No mobile viewport tag found. Your site may look tiny or broken on phones, where most Indian customers browse.');

        // --- SEO --------------------------------------------------------------
        $title = trim((string) $xpath?->query('//title')->item(0)?->textContent);
        $tl = mb_strlen($title);
        $checks[] = $this->check('seo', 'Page title', $tl >= 30 && $tl <= 65 ? 'pass' : ($tl > 0 ? 'warn' : 'fail'), 8,
            $tl ? "\"{$this->short($title)}\" ({$tl} characters). ".($tl >= 30 && $tl <= 65 ? 'Good length.' : 'Ideal length is 30–65 characters with your main service and city.') : 'Missing page title — Google has nothing to show in search results.');

        $desc = trim((string) $xpath?->query('//meta[@name="description"]/@content')->item(0)?->nodeValue);
        $dl = mb_strlen($desc);
        $checks[] = $this->check('seo', 'Meta description', $dl >= 70 && $dl <= 170 ? 'pass' : ($dl > 0 ? 'warn' : 'fail'), 6,
            $dl ? "{$dl} characters. ".($dl >= 70 && $dl <= 170 ? 'Good length.' : 'Aim for 70–170 characters that sell your service and include a call to action.') : 'Missing meta description — you lose control of how you appear in Google.');

        $h1 = $xpath?->query('//h1')->length ?? 0;
        $checks[] = $this->check('seo', 'Main heading (H1)', $h1 === 1 ? 'pass' : ($h1 > 1 ? 'warn' : 'fail'), 6,
            $h1 === 1 ? 'Exactly one H1 heading — perfect.' : ($h1 > 1 ? "{$h1} H1 headings found. Use one clear H1 describing what you do." : 'No H1 heading found. Add one clear headline describing your main service.'));

        $imgs = $xpath?->query('//img')->length ?? 0;
        $noAlt = $xpath?->query('//img[not(@alt) or normalize-space(@alt)=""]')->length ?? 0;
        $checks[] = $this->check('seo', 'Image alt text', $imgs === 0 || $noAlt === 0 ? 'pass' : ($noAlt / max($imgs, 1) <= 0.3 ? 'warn' : 'fail'), 4,
            $imgs === 0 ? 'No images found on the homepage.' : "{$noAlt} of {$imgs} images are missing alt text, which helps Google Images and accessibility.");

        $canonical = $xpath?->query('//link[@rel="canonical"]')->length > 0;
        $checks[] = $this->check('seo', 'Canonical tag', $canonical ? 'pass' : 'warn', 3,
            $canonical ? 'Canonical URL is set.' : 'No canonical tag. This can cause duplicate-content issues.');

        $schema = $xpath?->query('//script[@type="application/ld+json"]')->length > 0;
        $checks[] = $this->check('seo', 'Structured data (Schema)', $schema ? 'pass' : 'warn', 4,
            $schema ? 'Structured data found — helps rich results in Google.' : 'No structured data. Adding LocalBusiness/Organization schema can improve how you appear in search.');

        $base = ($https ? 'https://' : 'http://').$host;
        $robots = $this->fetch($base.'/robots.txt');
        $checks[] = $this->check('seo', 'robots.txt', $robots?->successful() ? 'pass' : 'warn', 3,
            $robots?->successful() ? 'robots.txt found.' : 'No robots.txt file found.');

        $sitemap = $this->fetch($base.'/sitemap.xml');
        $hasSitemap = $sitemap?->successful() && str_contains((string) $sitemap->body(), '<');
        $checks[] = $this->check('seo', 'XML sitemap', $hasSitemap ? 'pass' : 'warn', 4,
            $hasSitemap ? 'sitemap.xml found — Google can discover your pages easily.' : 'No sitemap.xml found at the standard location.');

        // --- Social & conversion ----------------------------------------------
        $og = $xpath?->query('//meta[@property="og:title"] | //meta[@property="og:image"]')->length ?? 0;
        $checks[] = $this->check('conversion', 'Social sharing preview (Open Graph)', $og >= 2 ? 'pass' : ($og ? 'warn' : 'fail'), 3,
            $og >= 2 ? 'Links shared on WhatsApp/Facebook will show a proper preview.' : 'Links shared on WhatsApp and Facebook will not show an attractive preview image and title.');

        $wa = (bool) preg_match('#wa\.me/|api\.whatsapp\.com|whatsapp://#i', $html);
        $checks[] = $this->check('conversion', 'WhatsApp chat button', $wa ? 'pass' : 'fail', 6,
            $wa ? 'Visitors can reach you on WhatsApp in one tap.' : 'No WhatsApp link found. Indian customers prefer WhatsApp — a chat button can significantly increase enquiries.');

        $tel = (bool) preg_match('#href=["\']tel:#i', $html);
        $checks[] = $this->check('conversion', 'Click-to-call link', $tel ? 'pass' : 'warn', 4,
            $tel ? 'Mobile visitors can call you with one tap.' : 'No click-to-call link found. Make your phone number tappable on mobile.');

        $form = ($xpath?->query('//form')->length ?? 0) > 0;
        $checks[] = $this->check('conversion', 'Enquiry form', $form ? 'pass' : 'warn', 4,
            $form ? 'An enquiry form is present on the homepage.' : 'No enquiry form on the homepage. Give visitors an easy way to request a quote.');

        $analytics = (bool) preg_match('#googletagmanager\.com|gtag\(|google-analytics\.com|fbq\(#i', $html);
        $checks[] = $this->check('conversion', 'Analytics / tracking installed', $analytics ? 'pass' : 'warn', 5,
            $analytics ? 'Tracking code detected — you can measure visitors and leads.' : 'No Google Analytics, Tag Manager or Meta Pixel found. You can\'t improve what you don\'t measure.');

        // --- Optional: Google PageSpeed Insights ------------------------------------------
        $performance = $this->pageSpeed($base.'/');
        if ($performance !== null) {
            $checks[] = $this->check('speed', 'Google PageSpeed (mobile)', $performance >= 70 ? 'pass' : ($performance >= 40 ? 'warn' : 'fail'), 10,
                "Google rates your mobile performance {$performance}/100. ".($performance >= 70 ? 'Good.' : 'Improving this helps both rankings and conversions.'));
        }

        return [
            'score' => $this->score($checks),
            'performance_score' => $performance,
            'checks' => $checks,
        ];
    }

    private function fetch(string $url): ?Response
    {
        try {
            // Re-validate host to prevent DNS-rebinding style redirects to internal IPs.
            $this->assertPublicHost((string) parse_url($url, PHP_URL_HOST));

            return Http::withHeaders(['User-Agent' => self::UA, 'Accept' => 'text/html,application/xhtml+xml,*/*'])
                ->timeout(12)
                ->connectTimeout(6)
                ->withOptions(['allow_redirects' => [
                    'max' => 3,
                    'protocols' => ['http', 'https'],
                    // Block redirects that point at internal / private addresses.
                    'on_redirect' => fn ($request, $response, $uri) => $this->assertPublicHost($uri->getHost()),
                ]])
                ->get($url);
        } catch (Throwable) {
            return null;
        }
    }

    private function pageSpeed(string $url): ?int
    {
        $key = config('advertally.pagespeed_api_key');
        if (! $key) {
            return null;
        }

        try {
            $score = Http::timeout(45)->get('https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                'url' => $url, 'strategy' => 'mobile', 'category' => 'performance', 'key' => $key,
            ])->json('lighthouseResult.categories.performance.score');

            return $score === null ? null : (int) round($score * 100);
        } catch (Throwable) {
            return null;
        }
    }

    private function xpath(string $html): ?DOMXPath
    {
        if ($html === '') {
            return null;
        }
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();

        return new DOMXPath($dom);
    }

    private function check(string $category, string $label, string $status, int $weight, string $detail): array
    {
        return compact('category', 'label', 'status', 'weight', 'detail');
    }

    private function score(array $checks): int
    {
        $total = array_sum(array_column($checks, 'weight'));
        $earned = array_sum(array_map(fn ($c) => $c['weight'] * match ($c['status']) {
            'pass' => 1.0, 'warn' => 0.5, default => 0.0,
        }, $checks));

        return $total ? (int) round($earned / $total * 100) : 0;
    }

    private function short(string $text, int $len = 70): string
    {
        return mb_strlen($text) > $len ? mb_substr($text, 0, $len).'…' : $text;
    }
}
