<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Cloudflare Turnstile verification. When no secret key is configured,
 * verification is skipped (honeypot + time trap + rate limiting still apply).
 */
class Turnstile
{
    public function enabled(): bool
    {
        return filled(config('advertally.turnstile.secret_key'));
    }

    public function verify(?string $token, ?string $ip = null): bool
    {
        if (! $this->enabled()) {
            return true;
        }

        if (blank($token)) {
            return false;
        }

        try {
            return (bool) Http::asForm()->timeout(5)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => config('advertally.turnstile.secret_key'),
                    'response' => $token,
                    'remoteip' => $ip,
                ])->json('success', false);
        } catch (Throwable) {
            // Fail open so a Cloudflare outage never blocks real enquiries.
            return true;
        }
    }
}
