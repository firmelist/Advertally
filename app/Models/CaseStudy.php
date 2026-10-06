<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseStudy extends Model
{
    use HasFactory, HasSeo, LogsActivity, Publishable;

    /** Story sections rendered in order on the case study page. */
    public const SECTIONS = [
        'challenge' => 'Business Challenge',
        'diagnosis' => 'Growth Diagnosis',
        'strategy' => 'Strategy',
        'execution' => 'Execution',
        'technology' => 'Technology',
        'results' => 'Results',
        'business_impact' => 'Business Impact',
    ];

    protected $fillable = [
        'industry_id', 'testimonial_id', 'client_name', 'is_sample', 'title', 'slug', 'summary', 'challenge', 'diagnosis',
        'strategy', 'execution', 'technology', 'results', 'business_impact', 'chart', 'featured_image', 'gallery',
        'is_featured', 'status', 'published_at',
    ];

    protected $casts = [
        'is_sample' => 'boolean', 'is_featured' => 'boolean', 'chart' => 'array', 'gallery' => 'array', 'published_at' => 'datetime',
    ];

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(CaseStudyMetric::class)->orderBy('sort_order');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function url(): string
    {
        return route('case-studies.show', $this->slug);
    }
}
