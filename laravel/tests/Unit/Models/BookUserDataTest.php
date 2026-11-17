<?php

namespace Tests\Unit\Models;

use App\Http\Resources\BookUserDatas\BookUserDataResource;
use App\Models\Book;
use App\Models\BookUserData;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели BookUserData: конфигурация без обращения к БД.
 * Составной PK (book_id, user_id) — отражён в свойствах модели.
 */
class BookUserDataTest extends TestCase
{
    public function test_table_name_is_book_user_data(): void
    {
        $this->assertSame(BookUserData::TABLE_NAME, (new BookUserData)->getTable());
    }

    public function test_fillable_contains_foreign_keys_and_user_fields(): void
    {
        $this->assertSame(
            [
                BookUserData::PARAM_BOOK_ID,
                BookUserData::PARAM_USER_ID,
                BookUserData::PARAM_RELEASE_DATE,
                BookUserData::PARAM_LISTEN_PRICE_VALUE,
                BookUserData::PARAM_COMMENT,
            ],
            (new BookUserData)->getFillable(),
        );
    }

    public function test_hidden_hides_foreign_keys(): void
    {
        $this->assertSame(
            [BookUserData::PARAM_BOOK_ID, BookUserData::PARAM_USER_ID],
            (new BookUserData)->getHidden(),
        );
    }

    public function test_primary_key_is_composite(): void
    {
        $property = new \ReflectionProperty(BookUserData::class, 'primaryKey');

        $this->assertSame(
            [BookUserData::PARAM_BOOK_ID, BookUserData::PARAM_USER_ID],
            $property->getValue(new BookUserData),
        );
    }

    public function test_incrementing_is_disabled(): void
    {
        $this->assertFalse((new BookUserData)->incrementing);
    }

    public function test_release_date_is_datetime_cast(): void
    {
        $this->assertSame(
            [BookUserData::PARAM_RELEASE_DATE => 'datetime'],
            // casts() protected — публичный доступ только через getCasts()
            (new BookUserData)->getCasts(),
        );
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $attributes = (new \ReflectionClass(BookUserData::class))
            ->getAttributes(UseResource::class);

        $this->assertNotEmpty($attributes);
        $this->assertSame(BookUserDataResource::class, $attributes[0]->newInstance()->class);
    }

    public function test_book_relation_is_belongs_to(): void
    {
        $relation = (new BookUserData)->book();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame(BookUserData::PARAM_BOOK_ID, $relation->getForeignKeyName());
        $this->assertSame(Book::class, $relation->getRelated()::class);
    }
}
