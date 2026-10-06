<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AuditRequest extends Model
{
    public const STATUSES = [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'completed' => 'Completed',
        'needs_review' => 'Needs analyst review',
        'failed' => 'Failed',
    ];

    protected $fillable = [
        'uuid', 'type', 'lead_id', 'name', 'email', 'company', 'website', 'industry', 'country', 'primary_service',
        'competitor', 'answers', 'status', 'overall_score', 'engine', 'signals', 'summary', 'error', 'ip_address', 'completed_at',
    ];

    protected $casts = ['answers' => 'array', 'signals' => 'array', 'completed_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(fn (self $audit) => $audit->uuid ??= (string) Str::uuid());
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(AuditScore::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(AuditRecommendation::class)->orderBy('sort_order');
    }

    public function url(): string
    {
        return $this->type === 'growth_score'
            ? route('growth-score.report', $this)
            : route('ai-audit.report', $this);
    }
}
