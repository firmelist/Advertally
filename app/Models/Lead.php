<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Model
{
    use HasFactory;

    public const FORM_TYPES = [
        'contact' => 'Contact form',
        'quote' => 'Quote request',
        'audit' => 'Free website audit',
        'hire' => 'Hire resources',
        'plan_builder' => 'Pricing calculator',
        'popup' => 'Exit-intent popup',
        'consultation' => 'Book consultation',
    ];

    /** Service interests shown on forms, grouped by ladder pillar. */
    public const SERVICE_OPTIONS = [
        'seo' => 'SEO',
        'google_ads' => 'Google Ads',
        'meta_ads' => 'Facebook / Instagram Ads',
        'social_media' => 'Social Media',
        'whatsapp_marketing' => 'WhatsApp Marketing',
        'website' => 'Website / E-commerce',
        'hire' => 'Hire Developer / Marketer',
        'crm' => 'CRM & Automation',
        'custom_software' => 'Custom Software / App',
    ];

    public const SERVICE_PILLAR = [
        'seo' => 'grow', 'google_ads' => 'grow', 'meta_ads' => 'grow', 'social_media' => 'grow', 'whatsapp_marketing' => 'grow',
        'website' => 'build', 'hire' => 'scale', 'crm' => 'automate', 'custom_software' => 'transform',
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'services' => 'array',
            'consent' => 'boolean',
            'next_follow_up_at' => 'datetime',
            'contacted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (Lead $lead) {
            if ($lead->isDirty('status') && $lead->status !== 'new' && ! $lead->contacted_at) {
                $lead->contacted_at = now();
            }
        });

        // Every status change is written to the activity timeline.
        static::updated(function (Lead $lead) {
            if ($lead->wasChanged('status')) {
                $labels = config('advertally.lead_statuses');
                $lead->notes()->create([
                    'user_id' => auth()->id(),
                    'type' => 'status',
                    'body' => 'Status changed from '.($labels[$lead->getOriginal('status')] ?? $lead->getOriginal('status'))
                        .' to '.($labels[$lead->status] ?? $lead->status).'.',
                ]);
            }
        });
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class)->latest();
    }

    public function auditReport(): HasOne
    {
        return $this->hasOne(AuditReport::class);
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    /** Sales executives only see leads assigned to them. */
    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if ($user && $user->isSales()) {
            $query->where('assigned_to', $user->id);
        }

        return $query;
    }

    public function getStatusLabelAttribute(): string
    {
        return config('advertally.lead_statuses')[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getServicesLabelAttribute(): string
    {
        return collect($this->services ?? [])
            ->map(fn ($s) => self::SERVICE_OPTIONS[$s] ?? $s)
            ->implode(', ');
    }

    public function getWhatsappUrlAttribute(): string
    {
        $phone = preg_replace('/\D/', '', (string) $this->phone);
        if (strlen($phone) === 10) {
            $phone = '91'.$phone;
        }

        return 'https://wa.me/'.$phone.'?text='.rawurlencode("Hi {$this->name}, this is Advertally. Thanks for your enquiry!");
    }

    public function getTemperatureAttribute(): string
    {
        return match (true) {
            $this->score >= 60 => 'hot',
            $this->score >= 35 => 'warm',
            default => 'cold',
        };
    }
}
