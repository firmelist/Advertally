<?php

namespace App\Http\Requests;

use App\Models\Lead;
use App\Services\Turnstile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $phone = preg_replace('/[^\d+]/', '', (string) $this->input('phone'));
        // Normalise Indian numbers: +91XXXXXXXXXX / 0XXXXXXXXXX → XXXXXXXXXX
        $phone = preg_replace('/^(\+?91|0)(?=\d{10}$)/', '', $phone);

        $this->merge([
            'phone' => $phone,
            'email' => $this->filled('email') ? strtolower(trim((string) $this->input('email'))) : null,
            'services' => array_values(array_filter((array) $this->input('services', []))),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^(\+?\d{10,15})$/'],
            'email' => ['nullable', 'email:rfc', 'max:190'],
            'company' => ['nullable', 'string', 'max:190'],
            'city' => ['nullable', 'string', 'max:80'],
            'website' => ['nullable', 'string', 'max:255'],
            'business_size' => ['nullable', Rule::in(array_keys(config('advertally.business_sizes')))],
            'industry' => ['nullable', Rule::in(array_keys(config('advertally.industries')))],
            'budget' => ['nullable', Rule::in(array_keys(config('advertally.budgets')))],
            'services' => ['array', 'max:9'],
            'services.*' => [Rule::in(array_keys(Lead::SERVICE_OPTIONS))],
            'message' => ['nullable', 'string', 'max:3000'],
            'form_type' => ['required', Rule::in(array_keys(Lead::FORM_TYPES))],
            'source_page' => ['nullable', 'string', 'max:500'],
            'form_id' => ['nullable', 'string', 'max:40'],
            'consent' => ['accepted'],
            // hire form extras (stored inside message)
            'role' => ['nullable', 'string', 'max:120'],
            'experience' => ['nullable', 'string', 'max:40'],
            'engagement' => ['nullable', 'string', 'max:40'],
            'start_date' => ['nullable', 'string', 'max:40'],
            // plan builder extras
            'plan_summary' => ['nullable', 'string', 'max:1000'],
            'plan_total' => ['nullable', 'string', 'max:60'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid 10-digit mobile number.',
            'consent.accepted' => 'Please agree to be contacted so we can reply to you.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                // Honeypot: real users never see or fill this field.
                if ($this->filled('company_website')) {
                    $validator->errors()->add('name', 'Something went wrong. Please try again.');
                }

                // Time trap: humans take more than 3 seconds to fill a form.
                $ts = (int) $this->input('_ts');
                if ($ts && (time() - $ts) < 3) {
                    $validator->errors()->add('name', 'Please take a moment and submit again.');
                }

                if (! app(Turnstile::class)->verify($this->input('cf-turnstile-response'), $this->ip())) {
                    $validator->errors()->add('name', 'Spam check failed. Please refresh and try again.');
                }
            },
        ];
    }
}
