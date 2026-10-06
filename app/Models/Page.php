<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasSeo, LogsActivity, Publishable;

    protected $fillable = ['title', 'slug', 'template', 'blocks', 'body', 'status'];

    protected $casts = ['blocks' => 'array'];

    public function url(): string
    {
        return $this->slug === 'home' ? route('home') : url($this->slug);
    }
}
