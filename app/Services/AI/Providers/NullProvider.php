<?php

namespace App\Services\AI\Providers;

use App\Services\AI\AiException;
use App\Services\AI\AiProvider;

/**
 * Used when AI_PROVIDER=none. Features must check available() and fall back to rule-based logic.
 */
class NullProvider implements AiProvider
{
    public function name(): string
    {
        return 'none';
    }

    public function available(): bool
    {
        return false;
    }

    public function complete(string $system, string $prompt, array $options = []): string
    {
        throw new AiException('No AI provider is configured.');
    }
}
