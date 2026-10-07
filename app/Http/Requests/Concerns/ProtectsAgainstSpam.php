<?php

namespace App\Http\Requests\Concerns;

use App\Services\Turnstile;
use Illuminate\Validation\Validator;

/**
 * Honeypot + time trap + optional Cloudflare Turnstile. Pair with the <x-form.guard /> Blade component.
 */
trait ProtectsAgainstSpam
{
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->filled('hp_trap')) {
                    $validator->errors()->add('email', 'Something went wrong. Please try again.');
                }

                $ts = (int) $this->input('_ts');
                if ($ts && (time() - $ts) < 3) {
                    $validator->errors()->add('email', 'Please take a moment and submit again.');
                }

                if (! app(Turnstile::class)->verify($this->input('cf-turnstile-response'), $this->ip())) {
                    $validator->errors()->add('email', 'Spam check failed. Please refresh and try again.');
                }
            },
        ];
    }
}
