<?php

namespace Tests\Unit\DTOs;

use App\DTOs\AuthorDTO;
use App\Models\Author;
use PHPUnit\Framework\TestCase;

class AuthorDTOTest extends TestCase
{
    public function test_from_array_maps_entity_data(): void
    {
        $dto = AuthorDTO::fromArray([Author::PARAM_FIRST_NAME => '???', Author::PARAM_LAST_NAME => '???????']);

        $this->assertSame([Author::PARAM_FIRST_NAME => '???', Author::PARAM_LAST_NAME => '???????'], $dto->getEntityData());
        $this->assertSame([], $dto->getRelationsData());
    }
}
