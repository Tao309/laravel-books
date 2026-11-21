<?php

namespace App\Http\Requests\Authors;

use App\DTOs\AuthorDTO;
use App\Http\Requests\RequestInterface;
use App\Models\Author;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractAuthorRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            Author::PARAM_FIRST_NAME . '.unique' => 'An Author with this name already exists',
        ];
    }

    public function toDTO(): AuthorDTO
    {
        return AuthorDTO::fromArray($this->validated());
    }

    protected function getCommonRules(): array
    {
        return [
            Author::PARAM_LAST_NAME => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':100',
            ],
        ];
    }
}
