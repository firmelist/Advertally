<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuditDimension extends Model
{
    use LogsActivity;

    public const TYPES = ['growth_score' => 'Growth Score', 'ai_visibility' => 'AI Visibility Audit'];

    protected $fillable = ['type', 'key', 'name', 'description', 'weight', 'recommendations', 'sort_order'];

    protected $casts = ['recommendations' => 'array'];

    public function questions(): HasMany
    {
        return $this->hasMany(GrowthScoreQuestion::class)->orderBy('sort_order');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type)->orderBy('sort_order');
    }

    /**
     * Recommendation templates whose threshold the score falls below, most urgent first.
     *
     * @return array<int, array{title:string, description:string, impact:string}>
     */
    public function recommendationsFor(int $score): array
    {
        return collect($this->recommendations ?? [])
            ->filter(fn ($r) => $score < (int) ($r['below'] ?? 0))
            ->sortBy('below')
            ->values()
            ->all();
    }
}
