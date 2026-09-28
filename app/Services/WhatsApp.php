<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Minimal WhatsApp Cloud API (Meta) client for approved template messages.
 * Swap the endpoint/payload here if you use a BSP such as Interakt, WATI or AiSensy.
 */
class WhatsApp
{
    public function enabled(): bool
    {
        return filled(config('advertally.whatsapp.token')) && filled(config('advertally.whatsapp.phone_number_id'));
    }

    /**
     * @param  array<int, string>  $params  Body variables {{1}}, {{2}} … in order.
     */
    public function sendTemplate(string $to, string $template, array $params = [], string $language = 'en'): bool
    {
        if (! $this->enabled()) {
            return false;
        }

        $to = preg_replace('/\D/', '', $to);
        if (strlen($to) === 10) {
            $to = '91'.$to;
        }

        $response = Http::withToken(config('advertally.whatsapp.token'))
            ->timeout(10)
            ->post(sprintf(
                'https://graph.facebook.com/%s/%s/messages',
                config('advertally.whatsapp.api_version'),
                config('advertally.whatsapp.phone_number_id'),
            ), [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => $template,
                    'language' => ['code' => $language],
                    'components' => $params ? [[
                        'type' => 'body',
                        'parameters' => array_map(fn ($p) => ['type' => 'text', 'text' => mb_substr((string) $p, 0, 200)], $params),
                    ]] : [],
                ],
            ]);

        if ($response->failed()) {
            Log::warning('WhatsApp send failed', ['to' => $to, 'template' => $template, 'body' => $response->body()]);
        }

        return $response->successful();
    }
}
