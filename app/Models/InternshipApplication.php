<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class InternshipApplication extends Model
{
    use LogsActivity;

    public const STATUSES = ['new' => 'New', 'shortlisted' => 'Shortlisted', 'interview' => 'Interview', 'offered' => 'Offered', 'rejected' => 'Not selected'];

    protected $fillable = [
        'internship_id', 'name', 'email', 'phone', 'city', 'education', 'graduation_year', 'linkedin_url', 'portfolio_url',
        'resume_path', 'resume_name', 'availability', 'motivation', 'status', 'notes', 'source_page', 'ip_address',
    ];

    protected static function booted(): void
    {
        static::deleted(fn (self $application) => $application->resume_path && Storage::disk('local')->delete($application->resume_path));
    }

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
