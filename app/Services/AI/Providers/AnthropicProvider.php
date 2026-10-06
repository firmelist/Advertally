<?php

namespace App\Services\AI\Providers;

class AnthropicProvider extends HttpProvider
{
    public function name(): string
    {
        return 'anthropic';
    }

    public function complete(string $system, string $prompt, array $options = []): string
    {
        return $this->guard(function () use ($system, $prompt, $options) {
            $response = $this->ensureOk($this->http()
                ->withHeaders(['x-api-key' => (string) $this->key, 'anthropic-version' => '2023-06-01'])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model' => $this->model,
                    'system' => $system.(($options['json'] ?? false) ? "\nRespond with valid JSON only." : ''),
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'max_tokens' => $options['max_tokens'] ?? 1200,
                ]));

            return collect($response->json('content', []))->where('type', 'text')->pluck('text')->implode("\n");
        });
    }
}
