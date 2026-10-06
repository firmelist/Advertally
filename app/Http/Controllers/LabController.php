<?php

namespace App\Http\Controllers;

use App\Models\AiResearch;
use App\Services\Seo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LabController extends Controller
{
    public function __construct(private Seo $seo) {}

    public function index(Request $request): View
    {
        $category = $request->validate(['category' => ['nullable', Rule::in(array_keys(AiResearch::CATEGORIES))]])['category'] ?? null;

        $this->seo->page('AI Search Lab: Research on AI Discovery',
            'Advertally AI Search Lab publishes research, experiments and frameworks on how AI assistants and search engines discover, evaluate and recommend businesses.')
            ->breadcrumbs(['AI Search Lab' => null]);

        $query = AiResearch::query()->published()->with('author')->latest('published_at');

        $featured = $category || $request->integer('page') > 1 ? null : (clone $query)->where('is_featured', true)->first();

        return view('lab.index', [
            'featured' => $featured,
            'research' => $query->when($category, fn ($q) => $q->where('category', $category))
                ->when($featured, fn ($q) => $q->whereKeyNot($featured->id))
                ->paginate(12)->withQueryString(),
            'activeCategory' => $category,
            'categories' => AiResearch::query()->published()->distinct()->pluck('category')
                ->mapWithKeys(fn ($c) => [$c => AiResearch::CATEGORIES[$c] ?? $c]),
        ]);
    }

    public function show(string $slug): View
    {
        $research = AiResearch::query()->published()->where('slug', $slug)
            ->with(['seo', 'author', 'services' => fn ($q) => $q->published()->with('category')])
            ->firstOrFail();

        $this->seo->page($research->title, $research->summary, $research)
            ->type('article')
            ->breadcrumbs(['AI Search Lab' => route('lab.index'), $research->title => null])
            ->schema(array_filter([
                '@type' => 'ScholarlyArticle',
                'headline' => $research->title,
                'abstract' => (string) $research->summary,
                'datePublished' => $research->published_at?->toIso8601String(),
                'dateModified' => $research->updated_at?->toIso8601String(),
                'author' => $research->author ? ['@id' => $research->author->url().'#author', 'name' => $research->author->name] : ['@id' => $this->seo->organizationId()],
                'publisher' => ['@id' => $this->seo->organizationId()],
                'citation' => collect($research->sources ?? [])->pluck('url')->filter()->values()->all() ?: null,
                'mainEntityOfPage' => $research->url(),
            ]));

        return view('lab.show', [
            'research' => $research,
            'more' => AiResearch::query()->published()->whereKeyNot($research->id)->latest('published_at')->take(3)->get(),
        ]);
    }
}
