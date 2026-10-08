<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * Admin-managed figures ("50+ Brands supported"). Never hard-coded; nothing shows until added and made visible.
 */
class Statistic extends Model
{
    use LogsActivity;

    protected $fillable = ['context', 'value', 'label', 'is_visible', 'sort_order'];

    protected $casts = ['is_visible' => 'boolean'];
}
