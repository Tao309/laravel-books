<?php

namespace Tests\Unit\Models;

use App\Http\Resources\PublishingHouses\PublishingHouseResource;
use App\Http\Resources\PublishingHouses\PublishingHousesResourceCollection;
use App\Models\PublishingHouse;
use App\Observers\PublishingHouseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели PublishingHouse: конфигурация без обращения к БД.
 */
class PublishingHouseTest extends TestCase
{
    public function test_table_name_is_publishing_houses(): void
    {
        $this->assertSame(PublishingHouse::TABLE_NAME, (new PublishingHouse)->getTable());
    }

    public function test_fillable_contains_only_name(): void
    {
        $this->assertSame([PublishingHouse::PARAM_NAME], (new PublishingHouse)->getFillable());
    }

    public function test_has_no_hidden_columns(): void
    {
        $this->assertSame([], (new PublishingHouse)->getHidden());
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $this->assertSame(
            PublishingHouseResource::class,
            $this->classAttribute(UseResource::class)->class,
        );
    }

    public function test_use_resource_collection_attribute_points_to_collection(): void
    {
        $this->assertSame(
            PublishingHousesResourceCollection::class,
            $this->classAttribute(UseResourceCollection::class)->class,
        );
    }

    public function test_observed_by_attribute_points_to_observer(): void
    {
        $observers = $this->classAttribute(ObservedBy::class)->classes;

        $this->assertContains(PublishingHouseObserver::class, $observers);
    }

    private function classAttribute(string $attributeClass): object
    {
        $attributes = (new \ReflectionClass(PublishingHouse::class))->getAttributes($attributeClass);

        $this->assertNotEmpty(
            $attributes,
            "Attribute {$attributeClass} is expected on ".PublishingHouse::class,
        );

        return $attributes[0]->newInstance();
    }
}
