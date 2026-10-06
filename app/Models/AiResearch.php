<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AiResearch extends Model
{
    use HasSeo, LogsActivity, Publishable;

    protected $table = 'ai_research';

    public const CATEGORIES = [
        'ai-search-research' => 'AI Search Research',
        'geo-experiments' => 'GEO Experiments',
        'ai-visibility-studies' => 'AI Visibility Studies',
        'google-ai-updates' => 'Google AI Updates',
        'chatgpt-search' => 'ChatGPT Search Research',
        'gemini' => 'Gemini Research',
        'perplexity' => 'Perplexity Research',
        'search-behavior' => 'Search Behavior',
        'b2b-ai-discovery' => 'B2B AI Discovery',
        'original-data' => 'Original Data',
        'frameworks' => 'Frameworks & Methodology',
    ];

    protected $fillable = [
        'author_id', 'category', 'title', 'slug', 'summary', 'key_findings', 'body', 'methodology', 'data', 'charts',
        'sources', 'is_featured', 'status', 'published_at',
    ];

    protected $casts = [
        'key_findings' => 'array', 'data' => 'array', 'charts' => 'array', 'sources' => 'array',
        'is_featured' => 'boolean', 'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'ai_research_service');
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? str($this->category)->headline()->toString();
    }

    public function url(): string
    {
        return route('lab.show', $this->slug);
    }
}
