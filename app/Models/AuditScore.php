<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditScore extends Model
{
    public $timestamps = false;

    protected $fillable = ['audit_request_id', 'audit_dimension_id', 'score', 'summary', 'checks'];

    protected $casts = ['checks' => 'array'];

    public function auditRequest(): BelongsTo
    {
        return $this->belongsTo(AuditRequest::class);
    }

    public function dimension(): BelongsTo
    {
        return $this->belongsTo(AuditDimension::class, 'audit_dimension_id');
    }
}
