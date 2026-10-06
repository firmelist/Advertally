<?php

namespace App\Services\AI;

use App\Services\AI\Providers\AnthropicProvider;
use App\Services\AI\Providers\GeminiProvider;
use App\Services\AI\Providers\NullProvider;
use App\Services\AI\Providers\OpenAiProvider;
use InvalidArgumentException;

/**
 * Resolves the configured AI provider (AI_PROVIDER in .env). Keys are read server-side only.
 */
class AiManager
{
    /** @var array<string, AiProvider> */
    private array $resolved = [];

    public function provider(?string $name = null): AiProvider
    {
        $name ??= (string) config('advertally.ai.default', 'none');

        return $this->resolved[$name] ??= $this->make($name);
    }

    /** True when a real provider is configured with a key. */
    public function enabled(): bool
    {
        return $this->provider()->available();
    }

    private function make(string $name): AiProvider
    {
        $config = (array) config("advertally.ai.providers.{$name}", []);
        $timeout = (int) config('advertally.ai.timeout', 30);

        return match ($name) {
            'none', '' => new NullProvider,
            'openai' => new OpenAiProvider($config['key'] ?? null, $config['model'] ?? '', $timeout),
            'anthropic' => new AnthropicProvider($config['key'] ?? null, $config['model'] ?? '', $timeout),
            'gemini' => new GeminiProvider($config['key'] ?? null, $config['model'] ?? '', $timeout),
            default => throw new InvalidArgumentException("Unknown AI provider [{$name}]."),
        };
    }
}
