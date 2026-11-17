<?php

namespace Tests\Unit\DTOs;

use App\DTOs\BookDTO;
use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\PaperType;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use PHPUnit\Framework\TestCase;

/**
 * BookDTO: из fromArray обязательные ключи title / publish_year /
 * binding_type / authors берутся напрямую ($data[...], не Arr::get) —
 * при их отсутствии PHP кидает Undefined array key Warning (PHPUnit
 * превращает его в ошибку). book_user_data — единственный ключ с
 * дефолтом ([]), остальные опциональные уходят в null.
 */
class BookDTOTest extends TestCase
{
    /**
     * Минимальный валидный DTO: конструктор требует все 19 аргументов,
     * поэтому опциональные подставляем дефолтами через хелпер.
     */
    private function make(array $overrides = []): BookDTO
    {
        $args = array_merge([
            'title' => 'Мастер и Маргарита',
            'publishYear' => '1967',
            'bindingType' => [BindingType::PARAM_ID => 3],
            'authors' => [[Author::PARAM_ID => 11]],
            'originalTitle' => null,
            'isbn' => null,
            'pages' => null,
            'circulation' => null,
            'size' => null,
            'livelibId' => null,
            'goodreadsId' => null,
            'fantlabId' => null,
            'livelibRating' => null,
            'goodreadsRating' => null,
            'publishingHouse' => null,
            'publishingBrand' => null,
            'bookSeries' => null,
            'paperType' => null,
            'bookUserData' => [],
        ], $overrides);

        return new BookDTO(...$args);
    }

    public function test_from_array_maps_required_scalars_and_relations(): void
    {
        $dto = BookDTO::fromArray([
            Book::PARAM_TITLE => 'Мастер и Маргарита',
            Book::PARAM_PUBLISH_YEAR => '1967',
            Book::PARAM_BINDING_TYPE => [BindingType::PARAM_ID => 3],
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => 11]],
        ]);

        $this->assertSame('Мастер и Маргарита', $dto->title);
        $this->assertSame('1967', $dto->publishYear);
        $this->assertSame([BindingType::PARAM_ID => 3], $dto->bindingType);
        $this->assertSame([[Author::PARAM_ID => 11]], $dto->authors);
    }

    public function test_from_array_defaults_optionals_to_null(): void
    {
        $dto = BookDTO::fromArray([
            Book::PARAM_TITLE => 'Мастер и Маргарита',
            Book::PARAM_PUBLISH_YEAR => '1967',
            Book::PARAM_BINDING_TYPE => [BindingType::PARAM_ID => 3],
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => 11]],
        ]);

        $this->assertNull($dto->originalTitle);
        $this->assertNull($dto->isbn);
        $this->assertNull($dto->pages);
        $this->assertNull($dto->circulation);
        $this->assertNull($dto->size);
        $this->assertNull($dto->livelibId);
        $this->assertNull($dto->goodreadsId);
        $this->assertNull($dto->fantlabId);
        $this->assertNull($dto->livelibRating);
        $this->assertNull($dto->goodreadsRating);
        $this->assertNull($dto->publishingHouse);
        $this->assertNull($dto->publishingBrand);
        $this->assertNull($dto->bookSeries);
        $this->assertNull($dto->paperType);
    }

    public function test_from_array_defaults_book_user_data_to_empty_array(): void
    {
        $dto = BookDTO::fromArray([
            Book::PARAM_TITLE => 'Мастер и Маргарита',
            Book::PARAM_PUBLISH_YEAR => '1967',
            Book::PARAM_BINDING_TYPE => [BindingType::PARAM_ID => 3],
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => 11]],
        ]);

        $this->assertSame([], $dto->bookUserData);
    }

    public function test_from_array_passes_optional_scalars_through(): void
    {
        $dto = BookDTO::fromArray([
            Book::PARAM_TITLE => 'Мастер и Маргарита',
            Book::PARAM_PUBLISH_YEAR => '1967',
            Book::PARAM_BINDING_TYPE => [BindingType::PARAM_ID => 3],
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => 11]],
            Book::PARAM_ISBN => '978-5-389-07961-8',
            Book::PARAM_PAGES => '480',
            Book::PARAM_LIVELIB_RATING => '4.5',
        ]);

        $this->assertSame('978-5-389-07961-8', $dto->isbn);
        $this->assertSame('480', $dto->pages);
        $this->assertSame('4.5', $dto->livelibRating);
    }

    public function test_entity_data_contains_twelve_scalars(): void
    {
        $dto = $this->make([
            'originalTitle' => 'Майстер і Маргарита',
            'isbn' => '978-5-389-07961-8',
            'goodreadsId' => '11803',
        ]);

        $this->assertSame([
            Book::PARAM_TITLE => 'Мастер и Маргарита',
            Book::PARAM_ORIGINAL_TITLE => 'Майстер і Маргарита',
            Book::PARAM_ISBN => '978-5-389-07961-8',
            Book::PARAM_PAGES => null,
            Book::PARAM_CIRCULATION => null,
            Book::PARAM_SIZE => null,
            Book::PARAM_PUBLISH_YEAR => '1967',
            Book::PARAM_LIVELIB_ID => null,
            Book::PARAM_GOODREADS_ID => '11803',
            Book::PARAM_FANTLAB_ID => null,
            Book::PARAM_LIVELIB_RATING => null,
            Book::PARAM_GOODREADS_RATING => null,
        ], $dto->getEntityData());
    }

    public function test_relations_data_extracts_ids_from_relation_arrays(): void
    {
        $dto = $this->make([
            'bindingType' => [BindingType::PARAM_ID => 3],
            'paperType' => [PaperType::PARAM_ID => 5],
            'publishingHouse' => [PublishingHouse::PARAM_ID => 7],
            'publishingBrand' => [PublishingBrand::PARAM_ID => 9],
            'bookSeries' => [BookSeries::PARAM_ID => 13],
        ]);

        $relations = $dto->getRelationsData();

        $this->assertSame(3, $relations[Book::PARAM_BINDING_TYPE]);
        $this->assertSame(5, $relations[Book::PARAM_PAPER_TYPE]);
        $this->assertSame(7, $relations[Book::PARAM_PUBLISHING_HOUSE]);
        $this->assertSame(9, $relations[Book::PARAM_PUBLISHING_BRAND]);
        $this->assertSame(13, $relations[Book::PARAM_BOOK_SERIES]);
    }

    public function test_relations_data_without_relations_yields_nulls(): void
    {
        $dto = $this->make();

        $relations = $dto->getRelationsData();

        $this->assertSame(3, $relations[Book::PARAM_BINDING_TYPE]);
        $this->assertNull($relations[Book::PARAM_PAPER_TYPE]);
        $this->assertNull($relations[Book::PARAM_PUBLISHING_HOUSE]);
        $this->assertNull($relations[Book::PARAM_PUBLISHING_BRAND]);
        $this->assertNull($relations[Book::PARAM_BOOK_SERIES]);
        $this->assertSame([], $relations[Book::PARAM_BOOK_USER_DATA]);
    }

    public function test_relations_data_plucks_author_ids(): void
    {
        $dto = $this->make([
            'authors' => [
                [Author::PARAM_ID => 11, Author::PARAM_FIRST_NAME => 'Лев'],
                [Author::PARAM_ID => 12, Author::PARAM_FIRST_NAME => 'Илья'],
            ],
        ]);

        $this->assertSame([11, 12], $dto->getRelationsData()[Book::PARAM_AUTHORS]);
    }

    public function test_to_array_merges_entity_and_relations_data(): void
    {
        $dto = $this->make();

        $array = $dto->toArray();

        // 12 скаляров entity + 7 ключей relations, без пересечений
        $this->assertSame(
            array_merge($dto->getEntityData(), $dto->getRelationsData()),
            $array,
        );
        $this->assertArrayHasKey(Book::PARAM_TITLE, $array);
        $this->assertArrayHasKey(Book::PARAM_AUTHORS, $array);
        $this->assertCount(19, $array);
    }
}
