<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory, HasSeo, LogsActivity, Publishable;

    public const TYPES = [
        'article' => 'Article',
        'report' => 'Report',
        'framework' => 'Framework',
        'research' => 'Research',
        'guide' => 'Guide',
    ];

    protected $fillable = [
        'post_category_id', 'author_id', 'type', 'title', 'slug', 'excerpt', 'content', 'featured_image', 'tags',
        'reading_time', 'is_featured', 'status', 'published_at',
    ];

    protected $casts = ['tags' => 'array', 'is_featured' => 'boolean', 'published_at' => 'datetime'];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            $post->reading_time = max(1, (int) ceil(str_word_count(strip_tags((string) $post->content)) / 220));
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(Industry::class);
    }

    public function url(): string
    {
        return route('insights.show', $this->slug);
    }
}
