<?php

namespace Tests\Unit\DTOs;

use App\DTOs\AuthorDTO;
use App\Models\Author;
use PHPUnit\Framework\TestCase;

class AuthorDTOTest extends TestCase
{
    public function test_from_array_maps_first_and_last_name(): void
    {
        $dto = AuthorDTO::fromArray([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ]);

        $this->assertSame('Лев', $dto->firstName);
        $this->assertSame('Толстой', $dto->lastName);
    }

    /**
     * Документация дефекта: при отсутствии last_name дефолт у Arr::get — []
     * (массив), а не null. Типизированное свойство string ловит TypeError
     * ещё в конструкторе — create/update без last_name роняют запрос
     * (см. feature-тесты API: HTTP 500).
     */
    public function test_from_array_without_last_name_throws_type_error(): void
    {
        $this->expectException(\TypeError::class);

        AuthorDTO::fromArray([
            Author::PARAM_FIRST_NAME => 'Лев',
        ]);
    }

    public function test_entity_data_contains_both_names(): void
    {
        $dto = new AuthorDTO('Лев', 'Толстой');

        $this->assertSame([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ], $dto->getEntityData());
    }

    public function test_relations_data_is_empty(): void
    {
        $dto = new AuthorDTO('Лев', 'Толстой');

        $this->assertSame([], $dto->getRelationsData());
    }

    public function test_to_array_merges_entity_and_relations_data(): void
    {
        $dto = new AuthorDTO('Лев', 'Толстой');

        $this->assertSame([
            Author::PARAM_FIRST_NAME => 'Лев',
            Author::PARAM_LAST_NAME => 'Толстой',
        ], $dto->toArray());
    }
}
