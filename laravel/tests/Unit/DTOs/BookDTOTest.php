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

class BookDTOTest extends TestCase
{
    public function test_from_array_maps_entity_and_relations(): void
    {
        $dto = BookDTO::fromArray([
            Book::PARAM_TITLE => '?????',
            Book::PARAM_PUBLISH_YEAR => 2024,
            Book::PARAM_BINDING_TYPE => [BindingType::PARAM_ID => 1],
            Book::PARAM_AUTHORS => [[Author::PARAM_ID => 2]],
            Book::PARAM_PUBLISHING_HOUSE => [PublishingHouse::PARAM_ID => 3],
            Book::PARAM_PUBLISHING_BRAND => [PublishingBrand::PARAM_ID => 4],
            Book::PARAM_BOOK_SERIES => [BookSeries::PARAM_ID => 5],
            Book::PARAM_PAPER_TYPE => [PaperType::PARAM_ID => 6],
        ]);

        $this->assertSame('?????', $dto->getEntityData()[Book::PARAM_TITLE]);
        $relations = $dto->getRelationsData();
        $this->assertSame(1, $relations[Book::PARAM_BINDING_TYPE]);
        $this->assertSame([2], $relations[Book::PARAM_AUTHORS]);
        $this->assertSame(3, $relations[Book::PARAM_PUBLISHING_HOUSE]);
    }
}
