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
            code: $data[City::PARAM_CODE],
            title: $data[City::PARAM_TITLE],
        );
    }

    public function toArray(): array
    {
        return [
            City::PARAM_CODE => $this->code,
            City::PARAM_TITLE => $this->title,
        ];
    }
}
