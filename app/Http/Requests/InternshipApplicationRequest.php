<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ProtectsAgainstSpam;
use Illuminate\Foundation\Http\FormRequest;

class InternshipApplicationRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[+\d\s().-]{7,30}$/'],
            'city' => ['nullable', 'string', 'max:80'],
            'education' => ['required', 'string', 'max:190'],
            'graduation_year' => ['nullable', 'digits:4', 'integer', 'between:2000,2035'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'availability' => ['nullable', 'string', 'max:120'],
            'motivation' => ['required', 'string', 'min:30', 'max:3000'],
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/octet-stream,application/zip', 'max:5120'],
            'source_page' => ['nullable', 'string', 'max:500'],
            'consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'resume.mimes' => 'Please upload your résumé as a PDF or Word document.',
            'resume.max' => 'Your résumé must be 5 MB or smaller.',
            'motivation.min' => 'Tell us a little more (at least a couple of sentences).',
            'consent.accepted' => 'Please agree so we can review your application.',
        ];
    }

    public function attributes(): array
    {
        return ['motivation' => 'why you want this internship', 'education' => 'college / course'];
    }
}
