<?php

namespace App\Http\Requests\EventParticipant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contactId' => [
                'nullable',
                'integer',
                Rule::exists('contacts', 'id')->where(
                    fn ($query) => $query->where('userId', $this->user()->id)
                ),
            ],

            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:180'],

            'role' => [
                'sometimes',
                Rule::in(['organizer', 'attendee']),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if (
                ! $this->filled('contactId')
                && ! $this->filled('name')
                && ! $this->filled('email')
            ) {
                $validator->errors()->add(
                    'participant',
                    'Informe um contato, nome ou e-mail para adicionar o participante.'
                );
            }
        });
    }
}