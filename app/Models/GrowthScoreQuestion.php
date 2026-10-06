<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthScoreQuestion extends Model
{
    use LogsActivity;

    protected $fillable = ['audit_dimension_id', 'question', 'help_text', 'options', 'is_active', 'sort_order'];

    protected $casts = ['options' => 'array', 'is_active' => 'boolean'];

    public function dimension(): BelongsTo
    {
        return $this->belongsTo(AuditDimension::class, 'audit_dimension_id');
    }
}
