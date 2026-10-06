<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * status = published (and, when the model has published_at, a publish date in the past).
 */
trait Publishable
{
    public const STATUSES = ['draft' => 'Draft', 'published' => 'Published'];

    public function scopePublished(Builder $query): Builder
    {
        $query->where($this->getTable().'.status', 'published');

        if (in_array('published_at', $this->getFillable(), true)) {
            $query->whereNotNull($this->getTable().'.published_at')->where($this->getTable().'.published_at', '<=', now());
        }

        return $query;
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
