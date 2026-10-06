<?php

namespace App\Services;

use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Request-scoped SEO state. Controllers describe the page; the layout renders meta tags and one JSON-LD @graph
 * (Organization + WebSite + BreadcrumbList + page-specific nodes). Admin overrides in seo_metadata win.
 */
class Seo
{
    public ?string $title = null;

    public ?string $description = null;

    public ?string $canonical = null;

    public string $robots = 'index,follow';

    public ?string $image = null;

    public string $type = 'website';

    /** @var array<int, array{name:string, url:string}> */
    public array $breadcrumbs = [];

    /** @var array<int, array> */
    private array $nodes = [];

    public function page(string $title, ?string $description = null, ?Model $model = null): static
    {
        $this->title = $title;
        $this->description = $description ? Str::limit(trim(strip_tags($description)), 158, '…') : null;

        if ($model && method_exists($model, 'seo')) {
            $this->apply($model->relationLoaded('seo') ? $model->seo : $model->seo()->first());
        }

        return $this;
    }

    public function apply(?SeoMetadata $meta): static
    {
        if (! $meta) {
            return $this;
        }

        $this->title = $meta->title ?: $this->title;
        $this->description = $meta->description ?: $this->description;
        $this->canonical = $meta->canonical ?: $this->canonical;
        $this->robots = $meta->robots ?: $this->robots;
        $this->image = $meta->og_image ? media_url($meta->og_image) : $this->image;

        foreach ((array) $meta->schema as $node) {
            if (is_array($node)) {
                $this->nodes[] = $node;
            }
        }

        return $this;
    }

    public function image(?string $pathOrUrl): static
    {
        $this->image = media_url($pathOrUrl) ?? $this->image;

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function noindex(): static
    {
        $this->robots = 'noindex,follow';

        return $this;
    }

    /** @param array<string, string|null> $trail name => url (null = current page) */
    public function breadcrumbs(array $trail): static
    {
        $this->breadcrumbs = [['name' => 'Home', 'url' => route('home')]];
        foreach ($trail as $name => $url) {
            $this->breadcrumbs[] = ['name' => $name, 'url' => $url ?? url()->current()];
        }

        return $this;
    }

    public function schema(array $node): static
    {
        $this->nodes[] = $node;

        return $this;
    }

    /** FAQPage only when the page genuinely shows those Q&As. */
    public function faq(array $faqs): static
    {
        $faqs = array_values(array_filter($faqs, fn ($f) => filled($f['question'] ?? null) && filled($f['answer'] ?? null)));

        if ($faqs) {
            $this->schema([
                '@type' => 'FAQPage',
                'mainEntity' => array_map(fn ($f) => [
                    '@type' => 'Question',
                    'name' => $f['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f['answer'])],
                ], $faqs),
            ]);
        }

        return $this;
    }

    public function service(string $name, string $description, string $url, ?string $category = null): static
    {
        return $this->schema(array_filter([
            '@type' => 'Service',
            '@id' => $url.'#service',
            'name' => $name,
            'description' => strip_tags($description),
            'url' => $url,
            'serviceType' => $category,
            'provider' => ['@id' => $this->organizationId()],
            'areaServed' => setting('area_served', 'Worldwide'),
        ]));
    }

    public function fullTitle(): string
    {
        $brand = setting('company_name', 'Advertally');
        $title = $this->title ?: setting('default_meta_title', $brand.' — AI-Native Growth & Revenue Partner');

        return str_contains($title, $brand) ? $title : "{$title} | {$brand}";
    }

    public function metaDescription(): string
    {
        return $this->description ?: (string) setting('default_meta_description',
            'Advertally builds AI-ready growth systems that help businesses get discovered, trusted and chosen across search, AI platforms and every digital touchpoint that drives revenue.');
    }

    public function canonicalUrl(): string
    {
        return $this->canonical ?: url()->current();
    }

    public function imageUrl(): string
    {
        return $this->image ?: (media_url(setting('default_og_image')) ?? asset('images/og-default.png'));
    }

    public function organizationId(): string
    {
        return url('/').'#organization';
    }

    public function graph(): array
    {
        $home = url('/');
        $sameAs = array_values(array_filter([
            setting('linkedin_url'), setting('youtube_url'), setting('x_url'), setting('instagram_url'), setting('facebook_url'),
        ]));

        $graph = [
            array_filter([
                '@type' => 'Organization',
                '@id' => $this->organizationId(),
                'name' => setting('company_name', 'Advertally'),
                'legalName' => setting('legal_name'),
                'url' => $home,
                'logo' => asset('images/advertally-logo.png'),
                'slogan' => 'AI-Native Growth & Revenue Partner',
                'description' => setting('company_description', 'AI-native growth and revenue partner for ambitious B2B businesses: AI search, demand, authority, conversion, automation and growth intelligence.'),
                'email' => setting('email'),
                'telephone' => setting('phone'),
                'address' => setting('address') ? ['@type' => 'PostalAddress', 'streetAddress' => setting('address'), 'addressCountry' => setting('country', 'IN')] : null,
                'sameAs' => $sameAs ?: null,
                'knowsAbout' => ['AI search optimisation', 'Generative engine optimisation', 'Search engine optimisation', 'B2B demand generation', 'Conversion rate optimisation', 'Marketing automation', 'Marketing analytics', 'Revenue attribution'],
            ]),
            [
                '@type' => 'WebSite',
                '@id' => $home.'#website',
                'url' => $home,
                'name' => setting('company_name', 'Advertally'),
                'publisher' => ['@id' => $this->organizationId()],
                'inLanguage' => 'en',
            ],
        ];

        if (count($this->breadcrumbs) > 1) {
            $graph[] = [
                '@type' => 'BreadcrumbList',
                'itemListElement' => array_map(fn ($crumb, $i) => [
                    '@type' => 'ListItem', 'position' => $i + 1, 'name' => $crumb['name'], 'item' => $crumb['url'],
                ], $this->breadcrumbs, array_keys($this->breadcrumbs)),
            ];
        }

        return ['@context' => 'https://schema.org', '@graph' => [...$graph, ...$this->nodes]];
    }
}
