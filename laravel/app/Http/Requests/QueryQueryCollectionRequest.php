<?php

namespace App\Http\Requests;

use App\DTOs\CollectionQueryDto;
use App\DTOs\CollectionQueryDtoInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class QueryQueryCollectionRequest extends FormRequest implements QueryCollectionRequestInterface
{
    public const string FIELD_SEARCH = 'search';

    public const string FIELD_IDS = 'ids';

    public const string FIELD_LIMIT = 'limit';

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            self::FIELD_IDS.'.string' => 'IDs is not correct.',
            self::FIELD_IDS.'.regex' => 'IDs is not correct.',
            self::FIELD_SEARCH.'.required' => 'Search value is required.',
            self::FIELD_SEARCH.'.min' => 'Search value must be at least :min.',
            self::FIELD_SEARCH.'.max' => 'Search value must be less than :max.',
        ];
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            self::FIELD_SEARCH => [
                'sometimes',
                function ($attribute, $value, $fail) {
                    if (! is_string($value) && ! is_int($value)) {
                        $fail('The search value must be a string or integer.');
                    }
                },
                'min:3',
                'max:20',
            ],
            self::FIELD_IDS => [
                'sometimes',
                'string',
                'regex:/^\d+(,\d+)*$/',
            ],
            'id' => ['sometimes', 'array'],
            'id.*' => ['required', 'integer', 'min:1'],
            self::FIELD_LIMIT => [
                'sometimes',
                'integer',
                'min_digits::1',
                'max_digits::3',
            ],
        ];
    }

    public function validationData(): array
    {
        $data = parent::validationData();

        if ($this->route(self::FIELD_IDS)) {
            $data = array_merge(
                $data,
                [self::FIELD_IDS => $this->route(self::FIELD_IDS)]
            );
        }

        if ($this->route(self::FIELD_SEARCH)) {
            $data = array_merge(
                $data,
                [self::FIELD_SEARCH => $this->route(self::FIELD_SEARCH)]
            );
        }

        if ($this->route(self::FIELD_LIMIT)) {
            $data = array_merge(
                $data,
                [self::FIELD_LIMIT => $this->route(self::FIELD_LIMIT)]
            );
        }

        return $data;
    }

    public function toDTO(): CollectionQueryDtoInterface
    {
        return CollectionQueryDto::fromArray($this->validated());
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('ids') && is_string($this->input('ids'))) {
            $ids = trim($this->input('ids'), ', ');

            $this->merge([
                'id' => explode(',', $ids),
            ]);
        }
    }
}
