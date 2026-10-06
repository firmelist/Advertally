<?php

namespace App\Http\Controllers;

use App\Models\AiResearch;
use App\Models\CaseStudy;
use App\Models\Page;
use App\Models\Post;
use App\Services\Seo;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(private Seo $seo) {}

    public function home(): View
    {
        $page = Page::query()->published()->where('slug', 'home')->with('seo')->firstOrFail();

        $this->seo->page(
            'Advertally — AI-Native Growth & Revenue Partner',
            'Advertally builds AI-ready growth systems that help businesses get discovered, trusted and chosen across search, AI platforms and every digital touchpoint that drives revenue.',
            $page,
        );

        return view('pages.blocks', ['page' => $page, 'isHome' => true]);
    }

    public function show(string $slug): View
    {
        $page = Page::query()->published()->where('slug', $slug)->with('seo')->firstOrFail();

        $this->seo->page($page->title, data_get($page->blocks, '0.data.subheadline') ?? strip_tags((string) $page->body), $page)
            ->breadcrumbs([$page->title => null]);

        return view($page->template === 'legal' ? 'pages.legal' : 'pages.blocks', ['page' => $page]);
    }

    public function resources(): View
    {
        $this->seo->page('Resources: Growth Intelligence for the AI Era',
            'Insights, AI Search Lab research, growth stories, reports and free diagnostic tools from Advertally.')
            ->breadcrumbs(['Resources' => null]);

        return view('pages.resources', [
            'posts' => Post::query()->published()->with('category', 'author')->latest('published_at')->take(3)->get(),
            'research' => AiResearch::query()->published()->latest('published_at')->take(3)->get(),
            'caseStudies' => CaseStudy::query()->published()->with('industry')->latest('published_at')->take(2)->get(),
            'reports' => Post::query()->published()->whereIn('type', ['report', 'framework'])->latest('published_at')->take(3)->get(),
        ]);
    }
}
