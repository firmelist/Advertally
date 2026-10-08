<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Brand → Project → Internship. A project can be shown publicly while its brand stays confidential
 * (the page then says "Confidential client" instead of the brand name).
 */
class ClientProject extends Model
{
    use LogsActivity;

    protected $fillable = [
        'brand_id', 'title', 'description', 'industry', 'department', 'services', 'technologies', 'image',
        'is_public', 'display_permission', 'status',
    ];

    protected $casts = ['services' => 'array', 'technologies' => 'array', 'is_public' => 'boolean'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function internshipLinks(): HasMany
    {
        return $this->hasMany(InternshipProject::class);
    }

    public function scopeDisplayable(Builder $query): Builder
    {
        return $query->where('client_projects.is_public', true)
            ->where('client_projects.display_permission', 'approved')
            ->where('client_projects.status', 'active');
    }

    /** Brand name only when the brand itself is cleared for public display. */
    public function publicClientLabel(): string
    {
        return $this->brand?->isDisplayable()
            ? $this->brand->name
            : 'Confidential client'.($this->industry ? ' · '.$this->industry : '');
    }
}
