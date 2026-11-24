<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductDTO;
use App\Models\Book;
use App\Models\City;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SourceProduct;
use PHPUnit\Framework\TestCase;

class ProductDTOTest extends TestCase
{
    public function test_from_array_maps_entity_and_relations(): void
    {
        $dto = ProductDTO::fromArray([
            Product::PARAM_SHOP_PRODUCT_ID => 'sp1',
            Product::PARAM_SHOP_PRODUCT_CODE => 'code',
            Product::PARAM_TITLE => '?????',
            Product::PARAM_SHOP => [Shop::PARAM_ID => 1],
            Product::PARAM_CITY => [City::PARAM_ID => 2],
            Product::PARAM_BOOK => [Book::PARAM_ID => 3],
            Product::PARAM_SOURCE_PRODUCT => [SourceProduct::PARAM_ID => 4],
        ]);

        $entity = $dto->getEntityData();
        $relations = $dto->getRelationsData();

        $this->assertSame('sp1', $entity[Product::PARAM_SHOP_PRODUCT_ID]);
        $this->assertSame(1, $relations[Product::PARAM_SHOP]);
        $this->assertSame(2, $relations[Product::PARAM_CITY]);
        $this->assertSame(3, $relations[Product::PARAM_BOOK]);
        $this->assertSame(4, $relations[Product::PARAM_SOURCE_PRODUCT]);
    }
}
