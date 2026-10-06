<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class Setting extends Model
{
    use LogsActivity;

    public const GROUPS = ['general' => 'Company', 'contact' => 'Contact', 'social' => 'Social profiles', 'seo' => 'SEO defaults'];

    protected $fillable = ['group', 'key', 'label', 'type', 'value'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings.all'));
        static::deleted(fn () => Cache::forget('settings.all'));
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $all = Cache::rememberForever('settings.all', fn () => Schema::hasTable('settings')
                ? static::query()->pluck('value', 'key')->all()
                : []);
        } catch (Throwable) {
            return $default;
        }

        return filled($all[$key] ?? null) ? $all[$key] : $default;
    }
}
