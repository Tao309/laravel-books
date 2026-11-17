<?php

namespace Tests\Unit\Models;

use App\Http\Resources\BookSeries\BookSeriesResource;
use App\Http\Resources\BookSeries\BookSeriesResourceCollection;
use App\Models\BookSeries;
use App\Observers\BookSeriesObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Tests\TestCase;

/**
 * Чистые unit-тесты модели BookSeries: конфигурация без обращения к БД.
 */
class BookSeriesTest extends TestCase
{
    public function test_table_name_is_books_series(): void
    {
        $this->assertSame(BookSeries::TABLE_NAME, (new BookSeries)->getTable());
    }

    /**
     * Документация дефекта: id попал в $fillable — модель допускает
     * массовое присвоение первичного ключа.
     */
    public function test_fillable_contains_id_and_name(): void
    {
        $this->assertSame(
            [BookSeries::PARAM_ID, BookSeries::PARAM_NAME],
            (new BookSeries)->getFillable(),
        );
    }

    public function test_has_no_hidden_columns(): void
    {
        $this->assertSame([], (new BookSeries)->getHidden());
    }

    public function test_use_resource_attribute_points_to_resource(): void
    {
        $this->assertSame(
            BookSeriesResource::class,
            $this->classAttribute(UseResource::class)->class,
        );
    }

    public function test_use_resource_collection_attribute_points_to_collection(): void
    {
        $this->assertSame(
            BookSeriesResourceCollection::class,
            $this->classAttribute(UseResourceCollection::class)->class,
        );
    }

    public function test_observed_by_attribute_points_to_observer(): void
    {
        $observers = $this->classAttribute(ObservedBy::class)->classes;

        $this->assertContains(BookSeriesObserver::class, $observers);
    }

    private function classAttribute(string $attributeClass): object
    {
        $attributes = (new \ReflectionClass(BookSeries::class))->getAttributes($attributeClass);

        $this->assertNotEmpty(
            $attributes,
            "Attribute {$attributeClass} is expected on ".BookSeries::class,
        );

        return $attributes[0]->newInstance();
    }
}
