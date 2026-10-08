<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Internship extends Model
{
    use HasFactory, HasSeo, LogsActivity, Publishable;

    public const MODES = ['remote' => 'Remote', 'hybrid' => 'Hybrid', 'onsite' => 'On-site'];

    /** Selectable brand-section headings (the field also accepts custom text). */
    public const BRAND_HEADINGS = [
        'Real Brands. Real Work. Real Experience.',
        'Real Brands. Real Work.',
        "Brands You'll Work With",
        'Work With Real Brands',
        'Real Clients. Real Projects.',
        'The Brands Behind Your Experience',
        'Work That Goes Beyond Practice',
        'Real Businesses. Real Challenges.',
        'Where Your Work Makes an Impact',
        'Real Brands. Real Marketing.',
        'Real Brands. Real Digital Products.',
        'Real Business Problems. Real AI Applications.',
    ];

    public const DEFAULT_BRANDS_DESCRIPTION = 'Get exposure to real brands and real business challenges while working alongside the Advertally team. Depending on your role, project allocation and confidentiality requirements, you may contribute to work supporting businesses across different industries.';

    public const DISCLAIMER = 'Brand/project exposure depends on role, project requirements, confidentiality and business needs. Not every intern will work directly with every brand shown.';

    protected $fillable = [
        'title', 'slug', 'department', 'headline', 'summary', 'location', 'work_mode', 'duration', 'stipend', 'openings',
        'start_date', 'apply_by', 'hours', 'why', 'team_intro', 'team_points', 'work_on', 'toolkit', 'skills', 'learn',
        'who_should_apply', 'requirements', 'benefits', 'journey', 'career_growth', 'career_paths', 'faqs',
        'show_brands', 'brands_heading', 'brands_tagline', 'brands_description', 'exposure', 'show_industries',
        'show_statistics', 'is_featured', 'status', 'sort_order',
    ];

    protected $casts = [
        'apply_by' => 'date',
        'why' => 'array', 'team_points' => 'array', 'work_on' => 'array', 'toolkit' => 'array', 'skills' => 'array',
        'learn' => 'array', 'who_should_apply' => 'array', 'requirements' => 'array', 'benefits' => 'array',
        'journey' => 'array', 'career_paths' => 'array', 'faqs' => 'array', 'exposure' => 'array',
        'show_brands' => 'boolean', 'show_industries' => 'boolean', 'show_statistics' => 'boolean', 'is_featured' => 'boolean',
    ];

    /** All associated brands (admin). Public pages must use publicBrands(). */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'internship_brand')->withPivot('sort_order')->orderByPivot('sort_order')->orderBy('brands.sort_order');
    }

    /** Only brands cleared for public display — confidential clients never leave the database. */
    public function publicBrands(): BelongsToMany
    {
        return $this->brands()->displayable();
    }

    public function projectLinks(): HasMany
    {
        return $this->hasMany(InternshipProject::class)->orderBy('sort_order');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(InternshipApplication::class);
    }

    public function isOpen(): bool
    {
        return $this->isPublished() && (! $this->apply_by || $this->apply_by->endOfDay()->isFuture());
    }

    public function url(): string
    {
        return route('internships.show', $this->slug);
    }
}
