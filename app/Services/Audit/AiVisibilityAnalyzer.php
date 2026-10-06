<?php

namespace App\Services\Audit;

use DOMDocument;
use DOMXPath;
use InvalidArgumentException;

/**
 * On-site AI visibility readiness: measures the signals AI assistants and search engines rely on
 * to crawl, understand, trust and cite a business. It does not claim to measure live AI citations —
 * that analysis is completed by an analyst or a configured AI provider later.
 *
 * Output: ['dimensions' => [key => ['score' => int, 'checks' => [...]]], 'facts' => [...]]
 */
class AiVisibilityAnalyzer
{
    /** AI crawlers whose access is checked in robots.txt. */
    private const AI_BOTS = ['GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'PerplexityBot', 'Google-Extended'];

    public function __construct(private SafeFetcher $fetcher) {}

    public function analyse(string $website, ?string $company = null): array
    {
        $url = $this->fetcher->normalise($website);
        $host = (string) parse_url($url, PHP_URL_HOST);

        $start = microtime(true);
        $page = $this->fetcher->get($url);
        $elapsed = round(microtime(true) - $start, 2);
        $https = (bool) $page?->successful();

        if (! $https) {
            $page = $this->fetcher->get('http://'.$host.'/');
        }

        if (! $page || ! $page->successful()) {
            throw new InvalidArgumentException('We could not open that website automatically (it may block bots or be offline).');
        }

        $base = ($https ? 'https://' : 'http://').$host;
        $html = (string) $page->body();
        $x = $this->xpath($html);
        // Read schema and links before visibleText() strips <script> nodes from the DOM.
        $schemas = $this->schemaTypes($x);
        $sameAs = $this->sameAs($x);
        $links = $this->links($x, $host);
        $text = $this->visibleText($x);
        $words = str_word_count($text);
        $robots = (string) ($this->fetcher->get($base.'/robots.txt', 8)?->body() ?? '');
        $hasLlms = $this->fetcher->get($base.'/llms.txt', 8)?->successful() ?? false;
        $sitemap = $this->fetcher->get($base.'/sitemap.xml', 8);
        $hasSitemap = ($sitemap?->successful() ?? false) && str_contains((string) $sitemap->body(), '<');
        $blockedBots = $this->blockedBots($robots);
        $title = trim((string) $x->query('//title')->item(0)?->textContent);
        $description = trim((string) $x->query('//meta[@name="description"]/@content')->item(0)?->nodeValue);
        $metaRobots = strtolower((string) $x->query('//meta[@name="robots"]/@content')->item(0)?->nodeValue);
        $h1 = $x->query('//h1')->length;
        $h2 = $x->query('//h2')->length;
        $brand = $company ?: $this->siteName($x, $host);

        $dimensions = [
            'ai_visibility' => $this->dimension([
                $this->check('AI crawler access', match (true) {
                    count($blockedBots) === 0 => 'pass',
                    count($blockedBots) < 3 => 'warn',
                    default => 'fail',
                }, 30, $blockedBots
                    ? 'robots.txt blocks: '.implode(', ', $blockedBots).'.'
                    : 'Major AI crawlers are not blocked in robots.txt.',
                    'Review robots.txt so the AI assistants your buyers use (ChatGPT, Claude, Perplexity, Gemini) can read your public pages.'),
                $this->check('Indexable homepage', str_contains($metaRobots, 'noindex') ? 'fail' : 'pass', 20,
                    str_contains($metaRobots, 'noindex') ? 'The homepage carries a noindex directive.' : 'The homepage can be indexed.',
                    'Remove the noindex directive from pages you want search engines and AI systems to surface.'),
                $this->check('Server-rendered content', $words >= 250 ? 'pass' : ($words >= 80 ? 'warn' : 'fail'), 25,
                    "About {$words} words of readable text are present in the raw HTML.",
                    'Make sure key messaging is in the HTML itself, not only rendered by JavaScript — many AI crawlers do not execute scripts.'),
                $this->check('llms.txt guide for AI', $hasLlms ? 'pass' : 'warn', 10,
                    $hasLlms ? 'An llms.txt file is published.' : 'No llms.txt file found.',
                    'Publish an llms.txt that summarises who you are, what you offer and your most important pages.'),
                $this->check('Answer-ready structure', $h2 >= 4 ? 'pass' : ($h2 >= 2 ? 'warn' : 'fail'), 15,
                    "{$h2} section headings (H2) found on the homepage.",
                    'Structure pages around the questions buyers ask, with clear headings and direct answers AI systems can quote.'),
            ]),
            'search_visibility' => $this->dimension([
                $this->check('HTTPS', $https ? 'pass' : 'fail', 15, $https ? 'Site loads over HTTPS.' : 'Site does not load over HTTPS.',
                    'Serve every page over HTTPS with a valid certificate.'),
                $this->check('Title tag', mb_strlen($title) >= 25 && mb_strlen($title) <= 65 ? 'pass' : ($title ? 'warn' : 'fail'), 15,
                    $title ? 'Title: "'.mb_strimwidth($title, 0, 70, '…').'"' : 'No title tag found.',
                    'Write a 25–65 character title that states what you do and for whom.'),
                $this->check('Meta description', mb_strlen($description) >= 70 && mb_strlen($description) <= 170 ? 'pass' : ($description ? 'warn' : 'fail'), 10,
                    $description ? mb_strlen($description).' characters.' : 'No meta description found.',
                    'Add a 70–170 character meta description that sells the outcome you deliver.'),
                $this->check('Single H1 heading', $h1 === 1 ? 'pass' : ($h1 > 1 ? 'warn' : 'fail'), 10,
                    "{$h1} H1 heading(s) on the homepage.", 'Use exactly one descriptive H1 per page.'),
                $this->check('XML sitemap', $hasSitemap ? 'pass' : 'warn', 15, $hasSitemap ? 'sitemap.xml found.' : 'No sitemap.xml at the standard location.',
                    'Publish an XML sitemap and submit it in Google Search Console and Bing Webmaster Tools.'),
                $this->check('Canonical URL', $x->query('//link[@rel="canonical"]')->length ? 'pass' : 'warn', 10,
                    $x->query('//link[@rel="canonical"]')->length ? 'Canonical tag present.' : 'No canonical tag.',
                    'Add canonical tags to prevent duplicate URLs diluting rankings.'),
                $this->check('Mobile viewport', $x->query('//meta[@name="viewport"]')->length ? 'pass' : 'fail', 10,
                    $x->query('//meta[@name="viewport"]')->length ? 'Mobile viewport configured.' : 'No mobile viewport tag.',
                    'Add a responsive viewport and test key pages on mobile.'),
                $this->check('Server response time', $elapsed <= 1.5 ? 'pass' : ($elapsed <= 3 ? 'warn' : 'fail'), 15,
                    "Homepage responded in {$elapsed}s.", 'Improve hosting, caching and page weight to respond in under 1.5 seconds.'),
            ]),
            'authority' => $this->dimension([
                $this->check('About / company page', $this->linksTo($links, ['about', 'company', 'who-we-are']) ? 'pass' : 'warn', 20,
                    $this->linksTo($links, ['about', 'company', 'who-we-are']) ? 'An about page is linked.' : 'No about/company page link found.',
                    'Publish a factual about page: founding story, leadership, locations and what makes you credible.'),
                $this->check('Proof of results', preg_match('/case stud|testimonial|client stor|success stor|reviews?/i', $text) ? 'pass' : 'fail', 30,
                    preg_match('/case stud|testimonial|client stor|success stor|reviews?/i', $text) ? 'Case studies, testimonials or reviews are referenced.' : 'No case studies, testimonials or reviews referenced on the homepage.',
                    'Publish verifiable case studies and client testimonials; link them from the homepage.'),
                $this->check('Expertise content', $this->linksTo($links, ['blog', 'insights', 'resources', 'articles', 'research', 'guides']) ? 'pass' : 'warn', 25,
                    $this->linksTo($links, ['blog', 'insights', 'resources', 'articles', 'research', 'guides']) ? 'A content hub (blog/insights/resources) is linked.' : 'No blog, insights or resources section linked.',
                    'Build an insights hub with expert, authored content that answers real buyer questions.'),
                $this->check('Social proof profiles', count($sameAs) >= 2 ? 'pass' : (count($sameAs) === 1 ? 'warn' : 'fail'), 25,
                    count($sameAs).' official social/profile link(s) found.',
                    'Link your official LinkedIn, YouTube and other profiles so systems can corroborate your brand.'),
            ]),
            'entity_strength' => $this->dimension([
                $this->check('Organization schema', array_intersect($schemas, ['Organization', 'Corporation', 'LocalBusiness', 'ProfessionalService']) ? 'pass' : 'fail', 35,
                    $schemas ? 'Schema types found: '.implode(', ', array_slice($schemas, 0, 8)).'.' : 'No JSON-LD structured data found.',
                    'Add Organization schema with your legal name, logo, contact points and sameAs profiles.'),
                $this->check('sameAs entity links', count($sameAs) >= 3 ? 'pass' : (count($sameAs) ? 'warn' : 'fail'), 25,
                    count($sameAs).' profile link(s) connect the brand to external entities.',
                    'Connect your entity to LinkedIn, Crunchbase, Google Business Profile and industry directories via sameAs.'),
                $this->check('Consistent brand name', $brand && str_contains(mb_strtolower($title.' '.$text), mb_strtolower($brand)) ? 'pass' : 'warn', 20,
                    $brand ? "Brand name \"{$brand}\" checked against page content." : 'Brand name could not be determined.',
                    'Use one consistent company name across your site, schema and profiles.'),
                $this->check('Open Graph identity', $x->query('//meta[@property="og:site_name"] | //meta[@property="og:image"]')->length >= 1 ? 'pass' : 'warn', 20,
                    $x->query('//meta[@property="og:site_name"] | //meta[@property="og:image"]')->length ? 'Open Graph identity tags present.' : 'Open Graph identity tags missing.',
                    'Add og:site_name, og:title and og:image so shared links represent the brand consistently.'),
            ]),
            'content_coverage' => $this->dimension([
                $this->check('Homepage depth', $words >= 600 ? 'pass' : ($words >= 250 ? 'warn' : 'fail'), 25,
                    "About {$words} words on the homepage.", 'Explain who you serve, problems you solve and outcomes you deliver in substantive copy.'),
                $this->check('Service pages', count(array_filter($links, fn ($l) => preg_match('#/(services?|solutions?|what-we-do|capabilities)#', $l))) >= 3 ? 'pass' : 'warn', 25,
                    'Service/solution links checked in navigation.', 'Create a dedicated page per core service, each answering what, who, how and why.'),
                $this->check('FAQ / answer content', in_array('FAQPage', $schemas, true) || preg_match('/frequently asked|faq/i', $text) ? 'pass' : 'warn', 20,
                    in_array('FAQPage', $schemas, true) ? 'FAQ schema present.' : 'No FAQ content detected on the homepage.',
                    'Add genuine FAQs to key pages and mark them up — they map directly to conversational queries.'),
                $this->check('Internal linking', count($links) >= 25 ? 'pass' : (count($links) >= 10 ? 'warn' : 'fail'), 15,
                    count($links).' internal links on the homepage.', 'Link related services, industries, insights and case studies to build topical depth.'),
                $this->check('Industry focus', $this->linksTo($links, ['industr', 'sectors', 'who-we-serve']) ? 'pass' : 'warn', 15,
                    $this->linksTo($links, ['industr', 'sectors', 'who-we-serve']) ? 'Industry pages are linked.' : 'No industry or sector pages linked.',
                    'Publish industry pages so AI systems associate your brand with the sectors you serve.'),
            ]),
            'conversion_readiness' => $this->dimension([
                $this->check('Lead capture form', $x->query('//form')->length ? 'pass' : 'warn', 25,
                    $x->query('//form')->length ? 'A form is present on the homepage.' : 'No form on the homepage.',
                    'Give high-intent visitors a short, clear way to start a conversation from every key page.'),
                $this->check('Clear call to action', preg_match('/(book|schedule|request|get started|talk to|contact us|demo|consultation|quote)/i', $text) ? 'pass' : 'fail', 25,
                    'Primary call-to-action language checked.', 'Use one primary call to action that names the next step and the value of taking it.'),
                $this->check('Direct contact routes', preg_match('#href=["\'](tel:|mailto:)#i', $html) ? 'pass' : 'warn', 15,
                    preg_match('#href=["\'](tel:|mailto:)#i', $html) ? 'Phone or email links present.' : 'No tappable phone or email links.',
                    'Make phone and email clickable for buyers who prefer to call or write.'),
                $this->check('Analytics installed', preg_match('#googletagmanager\.com|gtag\(|google-analytics\.com|clarity\.ms|snap\.licdn\.com|fbq\(#i', $html) ? 'pass' : 'fail', 20,
                    preg_match('#googletagmanager\.com|gtag\(|google-analytics\.com|clarity\.ms|snap\.licdn\.com|fbq\(#i', $html) ? 'Analytics or tag manager detected.' : 'No analytics or tag manager detected.',
                    'Install GA4 via Tag Manager and track form submissions as conversions.'),
                $this->check('Trust at decision point', preg_match('/privacy|secure|certified|iso|gdpr|partner/i', $text) ? 'pass' : 'warn', 15,
                    'Trust and reassurance language checked.', 'Add privacy reassurance, credentials and proof near forms and CTAs.'),
            ]),
        ];

        return [
            'dimensions' => $dimensions,
            'facts' => [
                'url' => $base.'/',
                'response_time' => $elapsed,
                'words' => $words,
                'schema_types' => $schemas,
                'same_as' => array_slice($sameAs, 0, 10),
                'blocked_ai_bots' => $blockedBots,
                'llms_txt' => $hasLlms,
                'sitemap' => $hasSitemap,
                'title' => $title,
                'brand' => $brand,
            ],
        ];
    }

    private function check(string $label, string $status, int $weight, string $detail, string $fix): array
    {
        return compact('label', 'status', 'weight', 'detail', 'fix');
    }

    private function dimension(array $checks): array
    {
        $total = array_sum(array_column($checks, 'weight'));
        $earned = array_sum(array_map(fn ($c) => $c['weight'] * match ($c['status']) {
            'pass' => 1.0, 'warn' => 0.5, default => 0.0,
        }, $checks));

        return ['score' => $total ? (int) round($earned / $total * 100) : 0, 'checks' => $checks];
    }

    private function xpath(string $html): DOMXPath
    {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.($html ?: '<html></html>'), LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();

        return new DOMXPath($dom);
    }

    private function visibleText(DOMXPath $x): string
    {
        foreach ($x->query('//script | //style | //noscript | //svg') as $node) {
            $node->parentNode?->removeChild($node);
        }

        return trim(preg_replace('/\s+/', ' ', (string) $x->query('//body')->item(0)?->textContent));
    }

    /** @return string[] */
    private function schemaTypes(DOMXPath $x): array
    {
        $types = [];
        foreach ($x->query('//script[@type="application/ld+json"]') as $node) {
            $json = json_decode(trim($node->textContent), true);
            if (! is_array($json)) {
                continue;
            }
            array_walk_recursive($json, function ($value, $key) use (&$types) {
                if ($key === '@type') {
                    $types[] = $value;
                }
            });
        }

        return array_values(array_unique(array_filter($types, 'is_string')));
    }

    /** @return string[] */
    private function sameAs(DOMXPath $x): array
    {
        $profiles = [];
        foreach ($x->query('//a/@href') as $href) {
            $value = (string) $href->nodeValue;
            if (preg_match('#^https?://(www\.)?(linkedin\.com/company|linkedin\.com/in|youtube\.com|x\.com|twitter\.com|facebook\.com|instagram\.com|crunchbase\.com|github\.com|clutch\.co|g2\.com)/#i', $value)) {
                $profiles[] = strtok($value, '?');
            }
        }

        return array_values(array_unique($profiles));
    }

    /** @return string[] internal link paths */
    private function links(DOMXPath $x, string $host): array
    {
        $paths = [];
        foreach ($x->query('//a/@href') as $href) {
            $value = trim((string) $href->nodeValue);
            $linkHost = parse_url($value, PHP_URL_HOST);
            if ($value === '' || str_starts_with($value, '#') || preg_match('#^(mailto|tel|javascript):#i', $value)) {
                continue;
            }
            if ($linkHost === null || preg_replace('/^www\./', '', strtolower($linkHost)) === preg_replace('/^www\./', '', $host)) {
                $paths[] = strtolower((string) (parse_url($value, PHP_URL_PATH) ?: '/'));
            }
        }

        return array_values(array_unique($paths));
    }

    private function linksTo(array $links, array $needles): bool
    {
        foreach ($links as $link) {
            foreach ($needles as $needle) {
                if (str_contains($link, $needle)) {
                    return true;
                }
            }
        }

        return false;
    }

    /** @return string[] AI bots fully disallowed by robots.txt (directly or via a "*" disallow-all). */
    private function blockedBots(string $robots): array
    {
        $groups = [];
        $agents = [];
        $lastWasAgent = false;

        foreach (preg_split('/\R/', $robots) as $line) {
            $line = trim(preg_replace('/#.*/', '', $line));
            if (! str_contains($line, ':')) {
                continue;
            }
            [$field, $value] = array_map('trim', explode(':', $line, 2));
            $field = strtolower($field);

            if ($field === 'user-agent') {
                $agents = $lastWasAgent ? [...$agents, strtolower($value)] : [strtolower($value)];
                $lastWasAgent = true;

                continue;
            }

            $lastWasAgent = false;
            if ($field === 'disallow' && $value === '/') {
                foreach ($agents as $agent) {
                    $groups[$agent] = true;
                }
            }
        }

        return array_values(array_filter(self::AI_BOTS, fn ($bot) => isset($groups[strtolower($bot)]) || isset($groups['*'])));
    }

    private function siteName(DOMXPath $x, string $host): string
    {
        $og = trim((string) $x->query('//meta[@property="og:site_name"]/@content')->item(0)?->nodeValue);

        return $og ?: ucfirst(explode('.', preg_replace('/^www\./', '', $host))[0]);
    }
}
