<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory, LogsActivity;

    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'opportunity' => 'Opportunity',
        'won' => 'Customer',
        'lost' => 'Lost',
        'spam' => 'Spam',
    ];

    /** Statuses that count as "qualified" for dashboard conversion metrics. */
    public const QUALIFIED = ['qualified', 'opportunity', 'won'];

    public const FORM_TYPES = [
        'contact' => 'Strategy request',
        'growth_score' => 'Growth Score',
        'ai_audit' => 'AI Visibility Audit',
        'talent' => 'Technology & Talent',
    ];

    protected $fillable = [
        'name', 'company', 'email', 'phone', 'website', 'job_title', 'industry', 'service_interest', 'challenge',
        'objective', 'budget', 'message', 'form_type', 'source', 'landing_page', 'source_page', 'referrer',
        'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
        'first_touch_source', 'first_touch_medium', 'first_touch_campaign', 'first_touch_landing_page', 'first_touch_at',
        'last_touch_source', 'gclid', 'fbclid', 'li_fat_id', 'device', 'ip_address', 'user_agent',
        'status', 'score', 'assigned_to', 'estimated_value', 'next_follow_up_at', 'notes', 'consent',
    ];

    protected $casts = [
        'first_touch_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
        'consent' => 'boolean',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->latest();
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ContactSubmission::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(AuditRequest::class);
    }

    public function scopeQualified(Builder $query): Builder
    {
        return $query->whereIn('status', self::QUALIFIED);
    }

    public function label(string $field): ?string
    {
        $value = $this->{$field};

        return match ($field) {
            'industry' => config("advertally.industries.{$value}", $value),
            'service_interest' => config("advertally.service_interests.{$value}", $value),
            'challenge' => config("advertally.challenges.{$value}", $value),
            'objective' => config("advertally.objectives.{$value}", $value),
            'budget' => config("advertally.budgets.{$value}", $value),
            'form_type' => self::FORM_TYPES[$value] ?? $value,
            'status' => self::STATUSES[$value] ?? $value,
            default => $value,
        };
    }
}
