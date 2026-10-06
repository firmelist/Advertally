<?php

namespace App\Services\AI\Providers;

class OpenAiProvider extends HttpProvider
{
    public function name(): string
    {
        return 'openai';
    }

    public function complete(string $system, string $prompt, array $options = []): string
    {
        return $this->guard(fn () => $this->ensureOk($this->http()->withToken((string) $this->key)
            ->post('https://api.openai.com/v1/chat/completions', array_filter([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => $options['max_tokens'] ?? 1200,
                'temperature' => $options['temperature'] ?? 0.3,
                'response_format' => ($options['json'] ?? false) ? ['type' => 'json_object'] : null,
            ])))->json('choices.0.message.content'));
    }
}
