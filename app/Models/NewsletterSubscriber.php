<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['email', 'name', 'source', 'status', 'token', 'unsubscribed_at'];

    protected $casts = ['unsubscribed_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(fn (self $subscriber) => $subscriber->token ??= Str::random(48));
    }
}
