<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    public const PAGES = [
        'home' => 'Home',
        'pricing' => 'Pricing',
        'hire' => 'Hire',
        'audit' => 'Free Audit',
        'contact' => 'Contact',
    ];

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where('page', $page)->where('is_active', true)->orderBy('sort_order');
    }
}
