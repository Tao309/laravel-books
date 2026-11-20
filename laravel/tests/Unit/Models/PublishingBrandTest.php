<?php

namespace Tests\Unit\Models;

use App\Http\Resources\PublishingBrand\PublishingBrandResource;
use App\Http\Resources\PublishingBrand\PublishingBrandsResourceCollection;
use App\Models\PublishingBrand;
use App\Observers\PublishingBrandObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели PublishingBrand: конфигурация без обращения к БД.
 */
class PublishingBrandTest extends TestCase
{
    public function test_table_name_is_publishing_brands(): void
    {
        $this->assertSame(PublishingBrand::TABLE_NAME, (new PublishingBrand)->getTable());
    }

    public function test_fillable_contains_only_name(): void
    {
        $this->assertSame([PublishingBrand::PARAM_NAME], (new PublishingBrand)->getFillable());
    }

    public function test_has_no_hidden_columns(): void
    {
        $this->assertSame([], (new PublishingBrand)->getHidden());
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $this->assertSame(
            PublishingBrandResource::class,
            $this->classAttribute(UseResource::class)->class,
        );
    }

    public function test_use_resource_collection_attribute_points_to_collection(): void
    {
        $this->assertSame(
            PublishingBrandsResourceCollection::class,
            $this->classAttribute(UseResourceCollection::class)->class,
        );
    }

    public function test_observed_by_attribute_points_to_observer(): void
    {
        $observers = $this->classAttribute(ObservedBy::class)->classes;

        $this->assertContains(PublishingBrandObserver::class, $observers);
    }

    private function classAttribute(string $attributeClass): object
    {
        $attributes = (new \ReflectionClass(PublishingBrand::class))->getAttributes($attributeClass);

        $this->assertNotEmpty(
            $attributes,
            "Attribute {$attributeClass} is expected on " . PublishingBrand::class,
        );

        return $attributes[0]->newInstance();
    }
}
