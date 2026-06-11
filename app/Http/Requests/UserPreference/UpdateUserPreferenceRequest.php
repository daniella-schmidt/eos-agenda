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
                'date_format:H:i',
            ],

            'preferredEndTime' => [
                'sometimes',
                'date_format:H:i',
                'after:preferredStartTime',
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