<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ProtectsAgainstSpam;
use Illuminate\Foundation\Http\FormRequest;

class NewsletterRequest extends FormRequest
{
    use ProtectsAgainstSpam;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['email' => strtolower(trim((string) $this->input('email')))]);
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:190'],
            'source_page' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function getRedirectUrl(): string
    {
        return parent::getRedirectUrl().'#newsletter';
    }
}
