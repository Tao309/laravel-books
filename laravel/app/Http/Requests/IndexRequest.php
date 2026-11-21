<?php

namespace App\Http\Requests;

use App\DTOs\CollectionQueryDto;
use App\DTOs\CollectionQueryDtoInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest implements IndexRequestInterface
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
            self::FIELD_IDS . '.' . RequestInterface::RULE_STRING => 'IDs is not correct.',
            self::FIELD_IDS . '.' . RequestInterface::RULE_REGEX => 'IDs is not correct.',
            self::FIELD_SEARCH . '.' . RequestInterface::RULE_REQUIRED => 'Search value is required.',
            self::FIELD_SEARCH . '.' . RequestInterface::RULE_MIN => 'Search value must be at least :min.',
            self::FIELD_SEARCH . '.' . RequestInterface::RULE_MAX => 'Search value must be less than :max.',
        ];
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            self::FIELD_SEARCH => [
                RequestInterface::RULE_SOMETIMES,
                function ($attribute, $value, $fail) {
                    if (! is_string($value) && ! is_int($value)) {
                        $fail('The search value must be a string or integer.');
                    }
                },
                RequestInterface::RULE_MIN . ':3',
                RequestInterface::RULE_MAX . ':200',
            ],
            self::FIELD_IDS => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_STRING,
                'regex:/^\d+(,\d+)*$/',
            ],
            'id' => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_ARRAY,
            ],
            'id.*' => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_INTEGER,
                RequestInterface::RULE_MIN . ':1',
            ],
            self::FIELD_LIMIT => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_INTEGER,
                RequestInterface::RULE_MIN . ':1',
                RequestInterface::RULE_MAX . ':100',
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
        if ($this->has(self::FIELD_IDS) && is_string($this->input(self::FIELD_IDS))) {
            $ids = trim($this->input(self::FIELD_IDS), ', ');

            $this->merge([
                'id' => explode(',', $ids),
            ]);
        }
    }
}
