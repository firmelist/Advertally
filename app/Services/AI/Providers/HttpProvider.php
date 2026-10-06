<?php

namespace App\Services\AI\Providers;

use App\Services\AI\AiException;
use App\Services\AI\AiProvider;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

abstract class HttpProvider implements AiProvider
{
    public function __construct(
        protected ?string $key,
        protected string $model,
        protected int $timeout = 30,
    ) {}

    public function available(): bool
    {
        return filled($this->key) && filled($this->model);
    }

    protected function http(): PendingRequest
    {
        if (! $this->available()) {
            throw new AiException("{$this->name()} is not configured.");
        }

        return Http::acceptJson()->timeout($this->timeout)->retry(2, 500, throw: false);
    }

    /** Fails loudly but without leaking the API key or full payload into logs. */
    protected function ensureOk(Response $response): Response
    {
        if ($response->failed()) {
            throw new AiException("{$this->name()} request failed with HTTP {$response->status()}.");
        }

        return $response;
    }

    protected function guard(callable $fn): string
    {
        try {
            $text = trim((string) $fn());
        } catch (AiException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new AiException("{$this->name()} request error: ".class_basename($e), previous: $e);
        }

        if ($text === '') {
            throw new AiException("{$this->name()} returned an empty response.");
        }

        return $text;
    }
}
