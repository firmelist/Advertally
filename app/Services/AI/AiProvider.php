<?php

namespace App\Services\AI;

/**
 * Provider-agnostic text generation contract. Future features (AI Visibility Monitor, lead summariser,
 * reporting agent …) depend on this interface — never on a vendor SDK.
 */
interface AiProvider
{
    public function name(): string;

    public function available(): bool;

    /**
     * @param  string  $system  Instructions / role for the model.
     * @param  string  $prompt  User content.
     * @param  array{max_tokens?:int, temperature?:float, json?:bool}  $options
     *
     * @throws AiException
     */
    public function complete(string $system, string $prompt, array $options = []): string;
}
