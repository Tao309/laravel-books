<?php

namespace Tests\Unit\Models;

use App\Http\Resources\BindingTypes\BindingTypeResource;
use App\Http\Resources\BindingTypes\BindingTypesResourceCollection;
use App\Models\BindingType;
use App\Observers\BindingTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели BindingType: конфигурация без обращения к БД.
 */
class BindingTypeTest extends TestCase
{
    public function test_table_name_is_binding_types(): void
    {
        $this->assertSame(BindingType::TABLE_NAME, (new BindingType)->getTable());
    }

    public function test_fillable_contains_only_label(): void
    {
        $this->assertSame([BindingType::PARAM_LABEL], (new BindingType)->getFillable());
    }

    public function test_has_no_hidden_columns(): void
    {
        $this->assertSame([], (new BindingType)->getHidden());
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $this->assertSame(
            BindingTypeResource::class,
            $this->classAttribute(UseResource::class)->class,
        );
    }

    public function test_use_resource_collection_attribute_points_to_collection(): void
    {
        $this->assertSame(
            BindingTypesResourceCollection::class,
            $this->classAttribute(UseResourceCollection::class)->class,
        );
    }

    public function test_observed_by_attribute_points_to_observer(): void
    {
        $observers = $this->classAttribute(ObservedBy::class)->classes;

        $this->assertContains(BindingTypeObserver::class, $observers);
    }

    private function classAttribute(string $attributeClass): object
    {
        $attributes = (new \ReflectionClass(BindingType::class))->getAttributes($attributeClass);

        $this->assertNotEmpty(
            $attributes,
            "Attribute {$attributeClass} is expected on ".BindingType::class,
        );

        return $attributes[0]->newInstance();
    }
}
