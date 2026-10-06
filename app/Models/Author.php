<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use LogsActivity;

    protected $fillable = ['user_id', 'type', 'name', 'slug', 'job_title', 'bio', 'photo', 'expertise', 'linkedin_url', 'x_url', 'is_active'];

    protected $casts = ['expertise' => 'array', 'is_active' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function research(): HasMany
    {
        return $this->hasMany(AiResearch::class);
    }

    public function isTeam(): bool
    {
        return $this->type === 'team';
    }

    public function url(): string
    {
        return route('authors.show', $this->slug);
    }
}
