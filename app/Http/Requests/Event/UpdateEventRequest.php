<?php

namespace App\Http\Requests\Event;

use App\Enums\EventPriority;
use App\Enums\EventStatus;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'calendarId' => [
                'sometimes',
                'integer',
                Rule::exists('calendars', 'id')->where(
                    fn (Builder $query) => $query
                        ->where('userId', $this->user()->id)
                        ->where('isActive', true)
                ),
            ],

            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],

            'startAt' => ['sometimes', 'date'],
            'endAt' => ['sometimes', 'date'],

            'timezone' => ['sometimes', 'timezone'],
            'location' => ['sometimes', 'nullable', 'string', 'max:500'],
            'meetingURL' => ['sometimes', 'nullable', 'url', 'max:1000'],

            'status' => [
                'sometimes',
                Rule::in([
                    EventStatus::Draft->value,
                    EventStatus::Confirmed->value,
                ]),
            ],

            'priority' => [
                'sometimes',
                Rule::enum(EventPriority::class),
            ],

            'isAllDay' => ['sometimes', 'boolean'],
            'isRecurring' => ['sometimes', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                $event = $this->route('event');

                $startAt = $this->input('startAt', $event?->startAt);
                $endAt = $this->input('endAt', $event?->endAt);

                if ($startAt && $endAt && strtotime($endAt) <= strtotime($startAt)) {
                    $validator->errors()->add(
                        'endAt',
                        'A data final deve ser posterior à data inicial.'
                    );
                }
            },
        ];
    }
}
