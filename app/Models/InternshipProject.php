<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipProject extends Model
{
    public $timestamps = false;

    protected $table = 'internship_project';

    protected $fillable = ['internship_id', 'client_project_id', 'intern_contribution', 'sort_order'];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(ClientProject::class, 'client_project_id');
    }
}
