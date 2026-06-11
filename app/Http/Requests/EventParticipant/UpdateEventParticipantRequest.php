<?php

namespace App\Http\Requests\EventParticipant;

use App\Enums\EventParticipantResponseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'nullable', 'string', 'max:120'],
            'email' => ['sometimes', 'nullable', 'email', 'max:180'],

            'role' => [
                'sometimes',
                Rule::in(['organizer', 'attendee']),
            ],

            'responseStatus' => [
                'sometimes',
                Rule::enum(EventParticipantResponseStatus::class),
            ],
        ];
    }
}