<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CaseStudy extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'services' => 'array',
            'results' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (CaseStudy $cs) {
            $cs->slug = $cs->slug ?: Str::slug($cs->title);
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getIndustryLabelAttribute(): ?string
    {
        return config('advertally.industries')[$this->industry] ?? $this->industry;
    }
}
