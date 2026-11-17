<?php

namespace Tests\Unit\DTOs;

use App\DTOs\CityDTO;
use App\Models\City;
use PHPUnit\Framework\TestCase;

class CityDTOTest extends TestCase
{
    public function test_from_array_maps_code_and_title(): void
    {
        $dto = CityDTO::fromArray([
            City::PARAM_CODE => 'KZN',
            City::PARAM_TITLE => 'Казань',
        ]);

        $this->assertSame('KZN', $dto->code);
        $this->assertSame('Казань', $dto->title);
    }

    public function test_entity_data_contains_code_and_title(): void
    {
        $dto = new CityDTO('KZN', 'Казань');

        $this->assertSame([
            City::PARAM_CODE => 'KZN',
            City::PARAM_TITLE => 'Казань',
        ], $dto->getEntityData());
    }

    public function test_relations_data_is_empty(): void
    {
        $dto = new CityDTO('KZN', 'Казань');

        $this->assertSame([], $dto->getRelationsData());
    }

    public function test_to_array_merges_entity_and_relations_data(): void
    {
        $dto = new CityDTO('KZN', 'Казань');

        $this->assertSame([
            City::PARAM_CODE => 'KZN',
            City::PARAM_TITLE => 'Казань',
        ], $dto->toArray());
    }

    /**
     * Документация дефекта: title в валидации store/update не required
     * ('string' без 'required'), поэтому без title валидация проходит,
     * но fromArray обращается к отсутствующему ключу. В Laravel E_WARNING
     * конвертируется в ErrorException (HandleExceptions) → HTTP 500
     * (см. CityApiTest); в plain PHPUnit это E_WARNING + TypeError,
     * E_WARNING глушится @, чтобы не попадать в отчёт как предупреждение.
     */
    public function test_from_array_without_title_fails(): void
    {
        $this->expectException(\TypeError::class);

        @CityDTO::fromArray([City::PARAM_CODE => 'KZN']);
    }
}
