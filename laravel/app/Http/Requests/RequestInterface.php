<?php

namespace App\Http\Requests;

use App\DTOs\DtoInterface;

interface RequestInterface
{
    public const string RULE_REQUIRED = 'required';

    public const string RULE_REQUIRED_WITHOUT = 'required_without';

    public const string RULE_NULLABLE = 'nullable';

    public const string RULE_SOMETIMES = 'sometimes';

    public const string RULE_STRING = 'string';

    public const string RULE_REGEX = 'regex';

    public const string RULE_INTEGER = 'integer';

    public const string RULE_BOOLEAN = 'boolean';

    public const string RULE_ARRAY = 'array';

    public const string RULE_MIN = 'min';

    public const string RULE_MAX = 'max';

    public const string RULE_DATE_FORMAT = 'date_format';

    public const string RULE_DECIMAL = 'decimal';

    public const string RULE_DIGITS = 'digits';

    public const string RULE_MIN_DIGITS = 'min_digits';

    public const string RULE_MAX_DIGITS = 'max_digits';

    public function messages(): array;

    public function toDTO(): DtoInterface;
}
