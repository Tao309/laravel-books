<?php

namespace Tests\Unit\Models;

use App\Http\Resources\SourceProductTypes\SourceProductTypeResource;
use App\Http\Resources\SourceProductTypes\SourceProductTypesResourceCollection;
use App\Models\SourceProductType;
use App\Observers\SourceProductTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели SourceProductType: конфигурация без обращения к БД.
 */
class SourceProductTypeTest extends TestCase
{
    public function test_table_name_is_source_product_types(): void
    {
        $this->assertSame(SourceProductType::TABLE_NAME, (new SourceProductType)->getTable());
    }

    public function test_fillable_contains_code_and_name(): void
    {
        $this->assertSame(
            [SourceProductType::PARAM_CODE, SourceProductType::PARAM_NAME],
            (new SourceProductType)->getFillable(),
        );
    }

    public function test_has_no_hidden_columns(): void
    {
        $this->assertSame([], (new SourceProductType)->getHidden());
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $this->assertSame(
            SourceProductTypeResource::class,
            $this->classAttribute(UseResource::class)->class,
        );
    }

    public function test_use_resource_collection_attribute_points_to_collection(): void
    {
        $this->assertSame(
            SourceProductTypesResourceCollection::class,
            $this->classAttribute(UseResourceCollection::class)->class,
        );
    }

    public function test_observed_by_attribute_points_to_observer(): void
    {
        $observers = $this->classAttribute(ObservedBy::class)->classes;

        $this->assertContains(SourceProductTypeObserver::class, $observers);
    }

    private function classAttribute(string $attributeClass): object
    {
        $attributes = (new \ReflectionClass(SourceProductType::class))->getAttributes($attributeClass);

        $this->assertNotEmpty(
            $attributes,
            "Attribute {$attributeClass} is expected on ".SourceProductType::class,
        );

        return $attributes[0]->newInstance();
    }
}
