<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStudyMetric extends Model
{
    public $timestamps = false;

    protected $fillable = ['case_study_id', 'label', 'value', 'before', 'after', 'description', 'sort_order'];

    public function caseStudy(): BelongsTo
    {
        return $this->belongsTo(CaseStudy::class);
    }
}
