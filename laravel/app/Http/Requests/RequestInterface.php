<?php

namespace App\Http\Requests;

use App\DTOs\DtoInterface;

interface RequestInterface
{
    public const RULE_REQUIRED = 'required';

    public const RULE_REQUIRED_WITHOUT = 'required_without';

    public const RULE_NULLABLE = 'nullable';

    public const RULE_SOMETIMES = 'sometimes';

    public const RULE_STRING = 'string';

    public const RULE_INTEGER = 'integer';

    public const RULE_BOOLEAN = 'boolean';

    public const RULE_ARRAY = 'array';

    public const RULE_MIN = 'min';

    public const RULE_MAX = 'max';

    public const RULE_BETWEEN = 'between';

    public const RULE_DATE_FORMAT = 'date_format';

    public const RULE_DECIMAL = 'decimal';

    public const RULE_DIGITS = 'digits';

    public const RULE_MIN_DIGITS = 'min_digits';

    public const RULE_MAX_DIGITS = 'max_digits';

    public function messages(): array;

    public function toDTO(): DtoInterface;
}
