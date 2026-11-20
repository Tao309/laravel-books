<?php

namespace Tests\Unit\Models;

use App\Http\Resources\PaperTypes\PaperTypeResource;
use App\Http\Resources\PaperTypes\PaperTypesResourceCollection;
use App\Models\PaperType;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели PaperType: конфигурация без обращения к БД.
 * У PaperType нет ObservedBy и своего кеш-провайдера — только CRUD.
 */
class PaperTypeTest extends TestCase
{
    public function test_table_name_is_paper_types(): void
    {
        $this->assertSame(PaperType::TABLE_NAME, (new PaperType)->getTable());
    }

    public function test_fillable_contains_label_and_description(): void
    {
        $this->assertSame(
            [PaperType::PARAM_LABEL, PaperType::PARAM_DESCRIPTION],
            (new PaperType)->getFillable(),
        );
    }

    public function test_has_no_hidden_columns(): void
    {
        $this->assertSame([], (new PaperType)->getHidden());
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $this->assertSame(
            PaperTypeResource::class,
            $this->classAttribute(UseResource::class)->class,
        );
    }

    public function test_use_resource_collection_attribute_points_to_collection(): void
    {
        $this->assertSame(
            PaperTypesResourceCollection::class,
            $this->classAttribute(UseResourceCollection::class)->class,
        );
    }

    private function classAttribute(string $attributeClass): object
    {
        $attributes = (new \ReflectionClass(PaperType::class))->getAttributes($attributeClass);

        $this->assertNotEmpty(
            $attributes,
            "Attribute {$attributeClass} is expected on " . PaperType::class,
        );

        return $attributes[0]->newInstance();
    }
}
