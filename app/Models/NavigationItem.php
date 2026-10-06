<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class NavigationItem extends Model
{
    use LogsActivity;

    public const MENUS = ['header' => 'Header mega menu', 'footer' => 'Footer columns', 'legal' => 'Footer legal links'];

    protected $fillable = ['menu', 'parent_id', 'label', 'url', 'description', 'icon', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }

    public static function flushCache(): void
    {
        foreach (array_keys(self::MENUS) as $menu) {
            Cache::forget("nav.{$menu}");
        }
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->where('is_active', true)->orderBy('sort_order');
    }

    /** Three-level active tree for a menu, cached until the next edit. */
    public static function tree(string $menu): Collection
    {
        return Cache::rememberForever("nav.{$menu}", fn () => static::query()
            ->where('menu', $menu)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with('children.children')
            ->orderBy('sort_order')
            ->get());
    }

    /** Relative URLs are resolved against the site root so the menu survives domain changes. */
    public function href(): string
    {
        $url = (string) $this->url;

        return $url === '' ? '#' : (preg_match('#^(https?:|mailto:|tel:|\#)#', $url) ? $url : url($url));
    }
}
