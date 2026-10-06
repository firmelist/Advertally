<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Post;
use App\Models\PostCategory;
use App\Services\Seo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InsightController extends Controller
{
    public function __construct(private Seo $seo) {}

    public function index(Request $request): View
    {
        $type = $request->validate(['type' => ['nullable', Rule::in(array_keys(Post::TYPES))]])['type'] ?? null;

        $this->seo->page($type ? Post::TYPES[$type].'s — Insights' : 'Insights: AI Search, B2B Growth & Revenue',
            'Practical thinking on AI search, SEO, B2B demand, authority, conversion, automation and analytics from the Advertally team.')
            ->breadcrumbs(['Insights' => null]);

        $featured = $type || $request->integer('page') > 1 ? null
            : Post::query()->published()->where('is_featured', true)->with('category', 'author')->latest('published_at')->first();

        return view('insights.index', [
            'posts' => $this->listing()->when($type, fn ($q) => $q->where('type', $type))
                ->when($featured, fn ($q) => $q->whereKeyNot($featured->id))
                ->paginate(12)->withQueryString(),
            'categories' => $this->categories(),
            'activeCategory' => null,
            'activeType' => $type,
            'featured' => $featured,
        ]);
    }

    public function category(string $slug): View
    {
        $category = PostCategory::query()->where('slug', $slug)->with('seo')->firstOrFail();

        $this->seo->page("{$category->name} Insights", $category->description, $category)
            ->breadcrumbs(['Insights' => route('insights.index'), $category->name => null]);

        return view('insights.index', [
            'posts' => $this->listing()->where('post_category_id', $category->id)->paginate(12),
            'categories' => $this->categories(),
            'activeCategory' => $category,
            'activeType' => null,
            'featured' => null,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::query()->published()->where('slug', $slug)
            ->with(['seo', 'category', 'author', 'services' => fn ($q) => $q->published()->with('category'), 'industries' => fn ($q) => $q->published()])
            ->firstOrFail();

        $authorNode = $post->author
            ? ['@type' => $post->author->isTeam() ? 'Organization' : 'Person', '@id' => $post->author->url().'#author', 'name' => $post->author->name, 'url' => $post->author->url()]
            : ['@id' => $this->seo->organizationId()];

        $this->seo->page($post->title, $post->excerpt, $post)
            ->image($post->featured_image)
            ->type('article')
            ->breadcrumbs(['Insights' => route('insights.index'), ...($post->category ? [$post->category->name => $post->category->url()] : []), $post->title => null])
            ->schema(array_filter([
                '@type' => $post->type === 'report' ? 'Report' : 'Article',
                'headline' => $post->title,
                'description' => (string) $post->excerpt,
                'image' => media_url($post->featured_image),
                'datePublished' => $post->published_at?->toIso8601String(),
                'dateModified' => $post->updated_at?->toIso8601String(),
                'author' => $authorNode,
                'publisher' => ['@id' => $this->seo->organizationId()],
                'articleSection' => $post->category?->name,
                'keywords' => $post->tags ? implode(', ', $post->tags) : null,
                'mainEntityOfPage' => $post->url(),
                'about' => $post->services->map(fn ($s) => ['@type' => 'Thing', 'name' => $s->title])->values()->all() ?: null,
            ]));

        $related = Post::query()->published()->whereKeyNot($post->id)
            ->where(fn ($q) => $q->where('post_category_id', $post->post_category_id)
                ->orWhereHas('services', fn ($s) => $s->whereIn('services.id', $post->services->pluck('id'))))
            ->latest('published_at')->take(3)->get();

        return view('insights.show', compact('post', 'related'));
    }

    public function author(string $slug): View
    {
        $author = Author::query()->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $this->seo->page("{$author->name}".($author->job_title ? " — {$author->job_title}" : ''), $author->bio)
            ->breadcrumbs(['Insights' => route('insights.index'), $author->name => null])
            ->schema(array_filter([
                '@type' => $author->isTeam() ? 'Organization' : 'Person',
                '@id' => $author->url().'#author',
                'name' => $author->name,
                'jobTitle' => $author->isTeam() ? null : $author->job_title,
                'description' => $author->bio,
                'image' => media_url($author->photo),
                'url' => $author->url(),
                'knowsAbout' => $author->expertise ?: null,
                'sameAs' => array_values(array_filter([$author->linkedin_url, $author->x_url])) ?: null,
                ($author->isTeam() ? 'parentOrganization' : 'worksFor') => ['@id' => $this->seo->organizationId()],
            ]));

        return view('insights.author', [
            'author' => $author,
            'posts' => $author->posts()->published()->with('category')->latest('published_at')->get(),
            'research' => $author->research()->published()->latest('published_at')->get(),
        ]);
    }

    private function listing()
    {
        return Post::query()->published()->with('category', 'author')->latest('published_at');
    }

    private function categories()
    {
        return PostCategory::query()->whereHas('posts', fn ($q) => $q->published())->orderBy('sort_order')->get();
    }
}
