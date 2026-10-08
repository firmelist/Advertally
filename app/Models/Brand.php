<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use App\Services\LogoOptimizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A client / brand Advertally works with. Confidential by default: a brand is only ever shown publicly when it is
 * public, approved for website display, active and enabled for internship pages (see scopeDisplayable).
 */
class Brand extends Model
{
    use HasFactory, LogsActivity;

    public const PERMISSIONS = ['approved' => 'Approved for website display', 'internal' => 'Internal use only'];

    protected $fillable = [
        'name', 'slug', 'logo', 'logo_variants', 'website_url', 'industry', 'category', 'description', 'work_summary',
        'is_public', 'display_permission', 'show_on_internships', 'status', 'sort_order',
    ];

    protected $casts = ['logo_variants' => 'array', 'is_public' => 'boolean', 'show_on_internships' => 'boolean'];

    protected static function booted(): void
    {
        // Optimise raster logos, generate responsive sizes and sanitise SVGs whenever the logo changes.
        static::saving(function (Brand $brand) {
            if ($brand->isDirty('logo') && $brand->logo) {
                $brand->logo_variants = app(LogoOptimizer::class)->process($brand->logo);
            }
        });
    }

    public function internships(): BelongsToMany
    {
        return $this->belongsToMany(Internship::class, 'internship_brand')->withPivot('sort_order');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(ClientProject::class);
    }

    /** The single source of truth for public display. */
    public function scopeDisplayable(Builder $query): Builder
    {
        return $query->where('brands.is_public', true)
            ->where('brands.display_permission', 'approved')
            ->where('brands.status', 'active')
            ->where('brands.show_on_internships', true);
    }

    public function isDisplayable(): bool
    {
        return $this->is_public && $this->display_permission === 'approved' && $this->status === 'active' && $this->show_on_internships;
    }

    /** srcset for responsive logos (raster only). */
    public function logoSrcset(): ?string
    {
        $variants = collect($this->logo_variants ?? [])->filter();

        return $variants->isEmpty() ? null : $variants->map(fn ($path, $w) => media_url($path)." {$w}w")->implode(', ');
    }
}
