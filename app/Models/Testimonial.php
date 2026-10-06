<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'role', 'company', 'quote', 'photo', 'is_published', 'sort_order'];

    protected $casts = ['is_published' => 'boolean'];
}
