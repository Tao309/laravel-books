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
            firstName: $data[Author::FIRST_NAME],
            lastName: $data[Author::LAST_NAME],
        );
    }

    public function toArray(): array
    {
        return [
            Author::FIRST_NAME => $this->firstName,
            Author::LAST_NAME => $this->lastName,
        ];
    }
}
