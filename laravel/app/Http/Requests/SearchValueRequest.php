<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchValueRequest extends FormRequest
{
    public const string FIELD_VALUE = 'value';

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            self::FIELD_VALUE.'.required' => 'Search value is required.',
            self::FIELD_VALUE.'.min' => 'Search value must be at least :min.',
            self::FIELD_VALUE.'.max' => 'Search value must be less than :max.',

        ];
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            self::FIELD_VALUE => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! is_string($value) && ! is_int($value)) {
                        $fail('The search value must be a string or integer.');
                    }
                },
                'min:3',
                'max:20',
            ],
        ];
    }

    public function validationData(): array
    {
        return array_merge(
            parent::validationData(),
            [self::FIELD_VALUE => $this->route(self::FIELD_VALUE)]
        );
    }
}
