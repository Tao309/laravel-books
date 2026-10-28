<?php

namespace App\Http\Requests\Authors;

use App\DTOs\AuthorDTO;
use App\Models\Author;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            Author::FIRST_NAME.'.unique' => 'An Author with this name already exists',
        ];
    }

    public function toDTO(): AuthorDTO
    {
        return AuthorDTO::fromArray($this->validated());
    }
}
