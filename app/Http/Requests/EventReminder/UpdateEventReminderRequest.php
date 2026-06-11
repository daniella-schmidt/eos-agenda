<?php

namespace App\Http\Requests\EventReminder;

use App\Enums\EventReminderType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'sometimes',
                Rule::enum(EventReminderType::class),
            ],

            'minutesBefore' => [
                'sometimes',
                'integer',
                'min:0',
                'max:10080',
            ],
        ];
    }
}