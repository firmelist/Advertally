<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['name', 'label', 'group'];

    /**
     * Permission areas. Each area gets "{area}.view", "{area}.manage" and "{area}.delete".
     */
    public const AREAS = [
        'leads' => 'Leads & submissions',
        'audits' => 'Audit requests & Growth Score',
        'services' => 'Solutions & services',
        'industries' => 'Industries',
        'case_studies' => 'Case studies',
        'insights' => 'Insights & authors',
        'research' => 'AI Search Lab',
        'testimonials' => 'Testimonials & logos',
        'pages' => 'Pages & navigation',
        'media' => 'Media library',
        'seo' => 'SEO',
        'settings' => 'Settings',
        'careers' => 'Internships, brands & projects',
        'applications' => 'Internship applications',
        'users' => 'Users & roles',
        'activity' => 'Activity log',
    ];

    public const ACTIONS = ['view' => 'View', 'manage' => 'Create & edit', 'delete' => 'Delete'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
