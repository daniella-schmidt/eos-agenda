<?php

namespace App\Http\Requests\UserPreference;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'defaultEventDurationMinutes' => [
                'sometimes',
                'integer',
                'min:5',
                'max:1440',
            ],

            'defaultMeetingDurationMinutes' => [
                'sometimes',
                'integer',
                'min:5',
                'max:480',
            ],

            'preferredStartTime' => [
                'sometimes',
                'nullable',
                'date_format:H:i,H:i:s',
            ],

            'preferredEndTime' => [
                'sometimes',
                'nullable',
                'date_format:H:i,H:i:s',
            ],

            'bufferBetweenEventsMinutes' => [
                'sometimes',
                'integer',
                'min:0',
                'max:180',
            ],

            'requireConfirmationBeforeCreate' => [
                'sometimes',
                'boolean',
            ],

            'autoCreateMeetingLink' => [
                'sometimes',
                'boolean',
            ],

            'autoCreateReminder' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}