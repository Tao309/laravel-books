<?php

namespace App\DTOs;

use App\Models\Author;
use Illuminate\Support\Arr;

class AuthorDTO extends AbstractDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data[Author::PARAM_FIRST_NAME],
            lastName: Arr::get($data, Author::PARAM_LAST_NAME, []),
        );
    }

    public function getEntityData(): array
    {
        return [
            Author::PARAM_FIRST_NAME => $this->firstName,
            Author::PARAM_LAST_NAME => $this->lastName,
        ];
    }

    public function getRelationsData(): array
    {
        return [];
    }
}
