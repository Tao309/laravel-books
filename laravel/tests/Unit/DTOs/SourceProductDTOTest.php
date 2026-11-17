<?php

namespace Tests\Unit\DTOs;

use App\DTOs\SourceProductDTO;
use App\Models\SourceProduct;
use PHPUnit\Framework\TestCase;

class SourceProductDTOTest extends TestCase
{
    public function test_from_array_maps_title_and_relations(): void
    {
        $dto = SourceProductDTO::fromArray([
            SourceProduct::PARAM_TITLE => 'Кроссовки лето',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => 7],
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                'comment' => 'Нужен размер 43',
                'listen_price_value' => 1500,
            ],
        ]);

        $this->assertSame('Кроссовки лето', $dto->title);
        $this->assertSame(['id' => 7], $dto->sourceProductType);
        $this->assertSame(
            ['comment' => 'Нужен размер 43', 'listen_price_value' => 1500],
            array_intersect_key($dto->sourceProductUserData, ['comment' => 0, 'listen_price_value' => 0]),
        );
    }

    public function test_from_array_without_user_data_defaults_to_empty_array(): void
    {
        $dto = SourceProductDTO::fromArray([
            SourceProduct::PARAM_TITLE => 'Кроссовки лето',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => ['id' => 7],
        ]);

        $this->assertSame([], $dto->sourceProductUserData);
    }

    public function test_entity_data_contains_only_title(): void
    {
        $dto = new SourceProductDTO('Кроссовки лето', ['id' => 7], []);

        $this->assertSame(
            [SourceProduct::PARAM_TITLE => 'Кроссовки лето'],
            $dto->getEntityData(),
        );
    }

    public function test_relations_data_extracts_type_id_from_object(): void
    {
        $dto = new SourceProductDTO('Кроссовки лето', ['id' => 7], [
            'listen_price_value' => 1500,
        ]);

        $relations = $dto->getRelationsData();

        $this->assertSame(7, $relations[SourceProduct::PARAM_SOURCE_PRODUCT_TYPE]);
        $this->assertSame(
            ['listen_price_value' => 1500],
            $relations[SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA],
        );
    }

    /**
     * Документация дефекта: скалярный id вместо ['id' => ...] не «фолбэчится»
     * в null — типизированное свойство array ловит TypeError в конструкторе.
     * Ассоциация с null на NOT NULL-констрейнте недостижима.
     */
    public function test_relations_data_with_scalar_type_throws_type_error(): void
    {
        $this->expectException(\TypeError::class);

        new SourceProductDTO('Кроссовки лето', 7, []);
    }
}
