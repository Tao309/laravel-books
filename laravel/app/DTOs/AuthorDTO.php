<?php

namespace App\DTOs;

use App\Models\Author;

class AuthorDTO implements DtoInterface
{
    public function __construct(
        public string $firstName,
        public string $lastName,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data[Author::PARAM_FIRST_NAME],
            lastName: $data[Author::PARAM_LAST_NAME],
        );
    }

    public function toArray(): array
    {
        return [
            Author::PARAM_FIRST_NAME => $this->firstName,
            Author::PARAM_LAST_NAME => $this->lastName,
        ];
    }
}
