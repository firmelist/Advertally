<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMetadata extends Model
{
    use LogsActivity;

    protected $table = 'seo_metadata';

    protected $fillable = ['title', 'description', 'canonical', 'robots', 'og_title', 'og_description', 'og_image', 'schema'];

    protected $casts = ['schema' => 'array'];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
