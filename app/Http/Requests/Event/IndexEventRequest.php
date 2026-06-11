<?php

namespace App\Http\Requests\Event;

use App\Enums\EventStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'calendarId' => ['sometimes', 'integer'],
            'status' => ['sometimes', Rule::enum(EventStatus::class)],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after:from'],
            'perPage' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}