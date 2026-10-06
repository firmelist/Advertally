<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ProtectsAgainstSpam;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Strategy request (contact page) and Technology & Talent enquiries.
 */
class ContactRequest extends FormRequest
{
    use ProtectsAgainstSpam;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim((string) $this->input('email'))),
            'website' => $this->filled('website') ? trim((string) $this->input('website')) : null,
            'form' => $this->input('form') === 'talent' ? 'talent' : 'contact',
        ]);
    }

    public function rules(): array
    {
        $talent = $this->input('form') === 'talent';

        return [
            'form' => ['required', Rule::in(['contact', 'talent'])],
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'company' => ['required', 'string', 'max:190'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[+\d\s().-]{7,30}$/'],
            'website' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'industry' => [$talent ? 'nullable' : 'required', Rule::in(array_keys(config('advertally.industries')))],
            'challenge' => ['nullable', Rule::in(array_keys(config('advertally.challenges')))],
            'objective' => ['nullable', Rule::in(array_keys(config('advertally.objectives')))],
            'budget' => ['nullable', Rule::in(array_keys(config('advertally.budgets')))],
            'service_interest' => ['nullable', Rule::in(array_keys(config('advertally.service_interests')))],
            'talent_role' => [$talent ? 'required' : 'nullable', Rule::in(array_keys(config('advertally.talent_roles')))],
            'message' => [$talent ? 'nullable' : 'required', 'string', 'max:3000'],
            'source_page' => ['nullable', 'string', 'max:500'],
            'consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Please use a valid business email address.',
            'message.required' => 'Tell us briefly how we can help.',
            'consent.accepted' => 'Please agree so we can respond to your request.',
        ];
    }

    public function attributes(): array
    {
        return ['talent_role' => 'role needed', 'industry' => 'industry'];
    }
}
