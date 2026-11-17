<?php

namespace Tests\Unit\Models;

use App\Http\Resources\Cities\CitiesResourceCollection;
use App\Http\Resources\Cities\CityResource;
use App\Models\City;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели City: конфигурация без обращения к БД.
 * У City нет ObservedBy и своего кеш-провайдера — только CRUD.
 */
class CityTest extends TestCase
{
    public function test_table_name_is_cities(): void
    {
        $this->assertSame(City::TABLE_NAME, (new City)->getTable());
    }

    public function test_fillable_contains_code_and_title(): void
    {
        $this->assertSame(
            [City::PARAM_CODE, City::PARAM_TITLE],
            (new City)->getFillable(),
        );
    }

    public function test_has_no_hidden_columns(): void
    {
        $this->assertSame([], (new City)->getHidden());
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $this->assertSame(
            CityResource::class,
            $this->classAttribute(UseResource::class)->class,
        );
    }

    public function test_use_resource_collection_attribute_points_to_collection(): void
    {
        $this->assertSame(
            CitiesResourceCollection::class,
            $this->classAttribute(UseResourceCollection::class)->class,
        );
    }

    private function classAttribute(string $attributeClass): object
    {
        $attributes = (new \ReflectionClass(City::class))->getAttributes($attributeClass);

        $this->assertNotEmpty(
            $attributes,
            "Attribute {$attributeClass} is expected on ".City::class,
        );

        return $attributes[0]->newInstance();
    }
}
