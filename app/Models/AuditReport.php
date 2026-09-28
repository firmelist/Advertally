<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AuditReport extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['checks' => 'array'];
    }

    protected static function booted(): void
    {
        static::creating(function (AuditReport $report) {
            $report->uuid ??= (string) Str::uuid();
        });
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getGradeAttribute(): string
    {
        return match (true) {
            $this->score >= 85 => 'Good',
            $this->score >= 60 => 'Needs work',
            default => 'Poor',
        };
    }
}
