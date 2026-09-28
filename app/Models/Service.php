<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'problems' => 'array',
            'deliverables' => 'array',
            'process' => 'array',
            'rate_card' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Service $service) {
            if (blank($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
            if ($service->parent_id && $service->parent) {
                $service->pillar = $service->parent->pillar;
            }
        });

        static::saved(fn () => Cache::forget('menu.hubs'));
        static::deleted(fn () => Cache::forget('menu.hubs'));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeHubs(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isHub(): bool
    {
        return $this->parent_id === null;
    }

    public function getUrlAttribute(): string
    {
        return $this->isHub()
            ? route('services.hub', $this->slug)
            : route('services.show', [$this->parent?->slug, $this->slug]);
    }

    public function getLadderAttribute(): array
    {
        return config("advertally.ladder.{$this->pillar}", []);
    }

    /** The next rung on the growth ladder — used for the upsell block on every service page. */
    public function nextHub(): ?self
    {
        $keys = array_keys(config('advertally.ladder'));
        $i = array_search($this->pillar, $keys, true);
        $next = $keys[$i + 1] ?? null;

        return $next ? self::query()->hubs()->active()->where('pillar', $next)->first() : null;
    }
}
