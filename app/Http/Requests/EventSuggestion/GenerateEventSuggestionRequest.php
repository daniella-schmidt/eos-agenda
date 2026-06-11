<?php

namespace App\Http\Requests\EventSuggestion;

use Illuminate\Foundation\Http\FormRequest;

class GenerateEventSuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'daysAhead' => ['sometimes', 'integer', 'min:1', 'max:30'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:10'],
        ];
    }
}