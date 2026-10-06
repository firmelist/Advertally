<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    use HasSeo, LogsActivity, Publishable;

    public const GROUPS = [
        'solution' => 'Growth OS solution',
        'technology' => 'Growth Technology',
        'talent' => 'Technology & Talent',
    ];

    protected $fillable = [
        'group', 'name', 'slug', 'number', 'tagline', 'headline', 'subheadline', 'summary', 'intro', 'icon',
        'flow_title', 'flow', 'metrics_title', 'metrics', 'capabilities', 'highlights', 'process', 'faqs',
        'principle', 'cta_label', 'cta_url', 'status', 'sort_order',
    ];

    protected $casts = [
        'flow' => 'array', 'metrics' => 'array', 'capabilities' => 'array',
        'highlights' => 'array', 'process' => 'array', 'faqs' => 'array',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class)->orderBy('sort_order');
    }

    public function scopeSolutions(Builder $query): Builder
    {
        return $query->where('group', 'solution')->orderBy('sort_order');
    }

    public function url(): string
    {
        return match ($this->group) {
            'technology' => route('growth-technology'),
            'talent' => route('talent'),
            default => route('solutions.show', $this->slug),
        };
    }
}
