<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory, HasSeo, LogsActivity, Publishable;

    protected $fillable = [
        'service_category_id', 'title', 'slug', 'short_description', 'hero_title', 'hero_subtitle', 'long_description',
        'benefits', 'deliverables', 'process', 'faqs', 'icon', 'is_featured', 'status', 'sort_order',
    ];

    protected $casts = [
        'benefits' => 'array', 'deliverables' => 'array', 'process' => 'array', 'faqs' => 'array', 'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function related(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'service_related', 'service_id', 'related_service_id');
    }

    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(Industry::class);
    }

    public function caseStudies(): BelongsToMany
    {
        return $this->belongsToMany(CaseStudy::class);
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function research(): BelongsToMany
    {
        return $this->belongsToMany(AiResearch::class, 'ai_research_service');
    }

    /** Each vertical has its own URL space so the buyer journeys stay separate. */
    public function url(): string
    {
        return match ($this->category?->group) {
            'technology' => route('growth-technology.show', $this->slug),
            'talent' => route('talent.show', $this->slug),
            default => route('services.show', $this->slug),
        };
    }

    /** Service-specific process, falling back to the engine's default delivery process. */
    public function processSteps(): array
    {
        return $this->process ?: ($this->category?->process ?? []);
    }
}
