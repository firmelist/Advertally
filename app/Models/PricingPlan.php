<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    public const CATEGORIES = [
        'marketing' => 'Digital Marketing',
        'websites' => 'Websites',
        'hire' => 'Hire Resources',
        'crm' => 'CRM & Automation',
        'maintenance' => 'Maintenance & AMC',
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getUnitLabelAttribute(): string
    {
        return match ($this->price_unit) {
            'month' => '/month',
            'hour' => '/hour',
            default => 'one-time',
        };
    }
}
