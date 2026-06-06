<?php

namespace App\Http\Requests\SmartRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreSmartRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rawText' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }
}
