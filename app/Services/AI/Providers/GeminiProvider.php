<?php

namespace App\Services\AI\Providers;

class GeminiProvider extends HttpProvider
{
    public function name(): string
    {
        return 'gemini';
    }

    public function complete(string $system, string $prompt, array $options = []): string
    {
        return $this->guard(fn () => $this->ensureOk($this->http()
            ->withHeaders(['x-goog-api-key' => (string) $this->key])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent", [
                'systemInstruction' => ['parts' => [['text' => $system]]],
                'contents' => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
                'generationConfig' => array_filter([
                    'maxOutputTokens' => $options['max_tokens'] ?? 1200,
                    'temperature' => $options['temperature'] ?? 0.3,
                    'responseMimeType' => ($options['json'] ?? false) ? 'application/json' : null,
                ]),
            ]))->json('candidates.0.content.parts.0.text'));
    }
}
