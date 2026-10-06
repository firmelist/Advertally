<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ProtectsAgainstSpam;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AiAuditRequest extends FormRequest
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
            'website' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:190'],
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'industry' => ['required', Rule::in(array_keys(config('advertally.industries')))],
            'country' => ['required', 'string', 'max:60'],
            'primary_service' => ['required', 'string', 'max:150'],
            'competitor' => ['nullable', 'string', 'max:255'],
            'consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return ['consent.accepted' => 'Please agree so we can send your report and follow up.'];
    }
}
