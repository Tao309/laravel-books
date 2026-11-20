<?php

namespace Tests\Unit\Models;

use App\Http\Resources\Shops\ShopResource;
use App\Http\Resources\Shops\ShopsResourceCollection;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели Shop: конфигурация без обращения к БД.
 * Shop доступен через API только на чтение (index/show) — командные
 * хендлеры есть, но не привязаны в ShopProvider (см. ShopsBusTest).
 */
class ShopTest extends TestCase
{
    public function test_table_name_is_shops(): void
    {
        $this->assertSame(Shop::TABLE_NAME, (new Shop)->getTable());
    }

    public function test_fillable_contains_type_domain_url(): void
    {
        $this->assertSame(
            [Shop::PARAM_TYPE, Shop::PARAM_DOMAIN, Shop::PARAM_URL],
            (new Shop)->getFillable(),
        );
    }

    public function test_has_no_hidden_columns(): void
    {
        $this->assertSame([], (new Shop)->getHidden());
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $this->assertSame(
            ShopResource::class,
            $this->classAttribute(UseResource::class)->class,
        );
    }

    public function test_use_resource_collection_attribute_points_to_collection(): void
    {
        $this->assertSame(
            ShopsResourceCollection::class,
            $this->classAttribute(UseResourceCollection::class)->class,
        );
    }

    private function classAttribute(string $attributeClass): object
    {
        $attributes = (new \ReflectionClass(Shop::class))->getAttributes($attributeClass);

        $this->assertNotEmpty(
            $attributes,
            "Attribute {$attributeClass} is expected on " . Shop::class,
        );

        return $attributes[0]->newInstance();
    }
}
