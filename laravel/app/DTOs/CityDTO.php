<?php

namespace App\DTOs;

use App\Models\City;

class CityDTO implements DtoInterface
{
    public function __construct(
        public string $code,
        public string $title,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data[City::CODE],
            title: $data[City::TITLE],
        );
    }

    public function toArray(): array
    {
        return [
            City::CODE => $this->code,
            City::TITLE => $this->title,
        ];
    }
}
