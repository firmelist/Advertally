<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'active_pillars' => 'array',
            'since' => 'date',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function accountManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_manager_id');
    }

    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if ($user && $user->isSales()) {
            $query->where('account_manager_id', $user->id);
        }

        return $query;
    }

    /**
     * Next service on the growth ladder the client does not use yet.
     * Grow → Build → Automate → Scale → Transform is the natural upsell order for SMEs
     * (a website and CRM usually come before dedicated hires and custom software).
     */
    public function getNextPillarAttribute(): ?string
    {
        $order = ['grow', 'build', 'automate', 'scale', 'transform'];
        $active = $this->active_pillars ?? [];

        foreach ($order as $pillar) {
            if (! in_array($pillar, $active, true)) {
                return $pillar;
            }
        }

        return null;
    }

    public function getNextPillarLabelAttribute(): ?string
    {
        $next = $this->next_pillar;

        return $next ? config("advertally.ladder.{$next}.title") : null;
    }
}
