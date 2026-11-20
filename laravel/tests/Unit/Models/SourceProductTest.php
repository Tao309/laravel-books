<?php

namespace Tests\Unit\Models;

use App\Http\Resources\SourceProducts\SourceProductResource;
use App\Http\Resources\SourceProducts\SourceProductsResourceCollection;
use App\Models\Product;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели SourceProduct: конфигурация без обращения к БД.
 */
class SourceProductTest extends TestCase
{
    public function test_table_name_is_source_products(): void
    {
        $this->assertSame(SourceProduct::TABLE_NAME, (new SourceProduct)->getTable());
    }

    public function test_fillable_contains_title_and_foreign_keys(): void
    {
        $this->assertSame(
            [
                SourceProduct::PARAM_TITLE,
                SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID,
                SourceProduct::PARAM_USER_CREATED_ID,
            ],
            (new SourceProduct)->getFillable(),
        );
    }

    public function test_hidden_contains_only_foreign_key_columns(): void
    {
        $this->assertSame(
            [SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID, SourceProduct::PARAM_USER_CREATED_ID],
            (new SourceProduct)->getHidden(),
        );
    }

    public function test_with_always_loads_three_relations(): void
    {
        $property = new \ReflectionProperty(SourceProduct::class, 'with');

        $this->assertSame(
            ['sourceProductType', 'userCreated', 'sourceProductUserData'],
            $property->getValue(new SourceProduct),
        );
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $this->assertSame(
            SourceProductResource::class,
            $this->classAttribute(UseResource::class)->class,
        );
    }

    public function test_use_resource_collection_attribute_points_to_collection(): void
    {
        $this->assertSame(
            SourceProductsResourceCollection::class,
            $this->classAttribute(UseResourceCollection::class)->class,
        );
    }

    public function test_source_product_type_relation_is_belongs_to(): void
    {
        $relation = (new SourceProduct)->sourceProductType();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame(SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID, $relation->getForeignKeyName());
        $this->assertSame(SourceProductType::class, $relation->getRelated()::class);
    }

    public function test_user_created_relation_is_belongs_to_user(): void
    {
        $relation = (new SourceProduct)->userCreated();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame(SourceProduct::PARAM_USER_CREATED_ID, $relation->getForeignKeyName());
        $this->assertSame(User::class, $relation->getRelated()::class);
    }

    public function test_source_product_user_data_relation_is_has_one_with_user_scope(): void
    {
        $relation = (new SourceProduct)->sourceProductUserData();

        $this->assertInstanceOf(HasOne::class, $relation);
        $this->assertSame(
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID,
            $relation->getForeignKeyName(),
        );
        $this->assertSame(SourceProductUserData::class, $relation->getRelated()::class);

        // Документация дефекта: скоуп связи читает Auth::id() ?? null ещё
        // на этапе определения связи. В unit-тесте без actingAs это null,
        // и условие рендерится как is null (привязки параметра нет).
        $this->assertStringContainsString(
            '"user_id" is null',
            $relation->getQuery()->toSql(),
        );
    }

    public function test_products_relation_is_morph_many(): void
    {
        $relation = (new SourceProduct)->products();

        $this->assertInstanceOf(MorphMany::class, $relation);
        $this->assertSame(Product::class, $relation->getRelated()::class);
    }

    private function classAttribute(string $attributeClass): object
    {
        $attributes = (new \ReflectionClass(SourceProduct::class))->getAttributes($attributeClass);

        $this->assertNotEmpty(
            $attributes,
            "Attribute {$attributeClass} is expected on " . SourceProduct::class,
        );

        return $attributes[0]->newInstance();
    }
}
