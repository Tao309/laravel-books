<?php

namespace Tests\Unit\Models;

use App\Http\Resources\Authors\AuthorResource;
use App\Http\Resources\Authors\AuthorsResourceCollection;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели Author: конфигурация без обращения к БД.
 */
class AuthorTest extends TestCase
{
    public function test_table_name_is_authors(): void
    {
        $this->assertSame(Author::TABLE_NAME, (new Author)->getTable());
    }

    public function test_fillable_contains_first_and_last_name(): void
    {
        $this->assertSame(
            [Author::PARAM_FIRST_NAME, Author::PARAM_LAST_NAME],
            (new Author)->getFillable(),
        );
    }

    public function test_per_page_is_100_not_default_15(): void
    {
        $this->assertSame(100, (new Author)->getPerPage());
    }

    public function test_hidden_contains_only_pivot(): void
    {
        $this->assertSame(['pivot'], (new Author)->getHidden());
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $this->assertSame(
            AuthorResource::class,
            $this->classAttribute(UseResource::class)->class,
        );
    }

    public function test_use_resource_collection_attribute_points_to_collection(): void
    {
        $this->assertSame(
            AuthorsResourceCollection::class,
            $this->classAttribute(UseResourceCollection::class)->class,
        );
    }

    public function test_books_relation_is_belongs_to_many_through_author_books(): void
    {
        $relation = (new Author)->books();

        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertSame(Author::PIVOT_AUTHOR_BOOKS_TABLE, $relation->getTable());
        $this->assertSame(Author::PIVOT_AUTHOR_BOOKS_AUTHOR_ID, $relation->getForeignPivotKeyName());
        $this->assertSame(Author::PIVOT_AUTHOR_BOOKS_BOOK_ID, $relation->getRelatedPivotKeyName());
        $this->assertSame(Book::class, $relation->getRelated()::class);
    }

    private function classAttribute(string $attributeClass): object
    {
        $attributes = (new \ReflectionClass(Author::class))->getAttributes($attributeClass);

        $this->assertNotEmpty(
            $attributes,
            "Attribute {$attributeClass} is expected on ".Author::class,
        );

        return $attributes[0]->newInstance();
    }
}
