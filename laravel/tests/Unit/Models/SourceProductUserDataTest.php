<?php

namespace Tests\Unit\Models;

use App\Http\Resources\SourceProductUserDatas\SourceProductUserDataResource;
use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели SourceProductUserData.
 *
 * Дефект именования: метод связи назван book(), но указывает на
 * SourceProduct (foreign key source_product_id) — копипаста из BookUserData.
 */
class SourceProductUserDataTest extends TestCase
{
    public function test_table_name_is_source_product_user_data(): void
    {
        $this->assertSame(SourceProductUserData::TABLE_NAME, (new SourceProductUserData)->getTable());
    }

    public function test_fillable_contains_foreign_keys_and_user_fields(): void
    {
        $this->assertSame(
            [
                SourceProductUserData::PARAM_SOURCE_PRODUCT_ID,
                SourceProductUserData::PARAM_USER_ID,
                SourceProductUserData::PARAM_LISTEN_PRICE_VALUE,
                SourceProductUserData::PARAM_COMMENT,
            ],
            (new SourceProductUserData)->getFillable(),
        );
    }

    public function test_hidden_hides_foreign_keys(): void
    {
        $this->assertSame(
            [SourceProductUserData::PARAM_SOURCE_PRODUCT_ID, SourceProductUserData::PARAM_USER_ID],
            (new SourceProductUserData)->getHidden(),
        );
    }

    public function test_primary_key_is_composite(): void
    {
        $property = new \ReflectionProperty(SourceProductUserData::class, 'primaryKey');

        $this->assertSame(
            [SourceProductUserData::PARAM_SOURCE_PRODUCT_ID, SourceProductUserData::PARAM_USER_ID],
            $property->getValue(new SourceProductUserData),
        );
    }

    public function test_incrementing_is_disabled(): void
    {
        $this->assertFalse((new SourceProductUserData)->incrementing);
    }

    public function test_model_has_no_datetime_casts(): void
    {
        // casts() protected — публичный доступ только через getCasts()
        $this->assertSame([], (new SourceProductUserData)->getCasts());
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $attributes = (new \ReflectionClass(SourceProductUserData::class))
            ->getAttributes(UseResource::class);

        $this->assertNotEmpty($attributes);
        $this->assertSame(SourceProductUserDataResource::class, $attributes[0]->newInstance()->class);
    }

    /**
     * Документация дефекта: связь называется book(), но ведёт на
     * SourceProduct — имя осталось от BookUserData.
     */
    public function test_book_named_relation_points_to_source_product(): void
    {
        $relation = (new SourceProductUserData)->book();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame(
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID,
            $relation->getForeignKeyName(),
        );
        $this->assertSame(SourceProduct::class, $relation->getRelated()::class);
    }
}
