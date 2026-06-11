<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $contact = $this->route('contact');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:120'],

            'email' => [
                'sometimes',
                'nullable',
                'email',
                'max:180',
                Rule::unique('contacts', 'email')
                    ->where(fn ($query) => $query->where('userId', $this->user()->id))
                    ->ignore($contact?->id),
            ],

            'phone' => ['sometimes', 'nullable', 'string', 'max:40'],
            'company' => ['sometimes', 'nullable', 'string', 'max:120'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }
}