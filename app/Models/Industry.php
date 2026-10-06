<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Industry extends Model
{
    use HasFactory, HasSeo, LogsActivity, Publishable;

    protected $fillable = [
        'name', 'slug', 'headline', 'summary', 'how_customers_search', 'ai_discovery', 'challenges',
        'opportunities', 'growth_system', 'faqs', 'icon', 'status', 'sort_order',
    ];

    protected $casts = ['challenges' => 'array', 'opportunities' => 'array', 'growth_system' => 'array', 'faqs' => 'array'];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function caseStudies(): HasMany
    {
        return $this->hasMany(CaseStudy::class);
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function url(): string
    {
        return route('industries.show', $this->slug);
    }
}
