<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class ClientLogo extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'logo', 'url', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];
}
