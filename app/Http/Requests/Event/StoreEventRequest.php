<?php

namespace App\Http\Requests\Event;

use App\Enums\EventPriority;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'calendarId' => [
                'nullable',
                'integer',
                Rule::exists('calendars', 'id')->where(
                    fn (Builder $query) => $query
                        ->where('userId', $this->user()->id)
                        ->where('isActive', true)
                ),
            ],

            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],

            'startAt' => ['required', 'date'],
            'endAt' => ['required', 'date', 'after:startAt'],

            'timezone' => ['nullable', 'timezone'],
            'location' => ['nullable', 'string', 'max:500'],
            'meetingURL' => ['nullable', 'url', 'max:1000'],

            'priority' => [
                'sometimes',
                Rule::enum(EventPriority::class),
            ],

            'isAllDay' => ['sometimes', 'boolean'],
        ];
    }
}
