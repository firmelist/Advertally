<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (! function_exists('setting')) {
    /**
     * Read a site setting managed from the admin panel (cached).
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('media_url')) {
    /**
     * Public URL for an uploaded file path (or pass through absolute URLs).
     */
    function media_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return preg_match('#^(https?:)?//#', $path) ? $path : Storage::disk('public')->url($path);
    }
}

if (! function_exists('score_tone')) {
    /**
     * Semantic tone for a 0–100 score. Green is reserved for genuinely strong outcomes.
     */
    function score_tone(?int $score): string
    {
        return match (true) {
            $score === null => 'muted',
            $score >= 75 => 'strong',
            $score >= 50 => 'fair',
            default => 'weak',
        };
    }
}
