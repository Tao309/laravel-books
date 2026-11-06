<?php

namespace App\DTOs;

use App\Models\Book;
use Illuminate\Support\Arr;

class BookDTO implements DtoInterface
{
    public function __construct(
        public string $title,
        public string $publishYear,
        public array $bindingType,
        public array $authors,
        public ?string $originalTitle,
        public ?string $isbn,
        public ?string $pages,
        public ?string $circulation,
        public ?string $size,
        public ?string $livelibId,
        public ?string $goodreadsId,
        public ?string $fantlabId,
        public ?string $livelibRating,
        public ?string $goodreadsRating,
        public ?array $publishingHouse,
        public ?array $publishingBrand,
        public ?array $bookSeries,
        public ?array $paperType,
        public ?array $bookUserData,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data[Book::PARAM_TITLE],
            publishYear: $data[Book::PARAM_PUBLISH_YEAR],
            bindingType: $data[Book::PARAM_BINDING_TYPE],
            authors: $data[Book::PARAM_AUTHORS],
            originalTitle: $data[Book::PARAM_ORIGINAL_TITLE] ?? null,
            isbn: $data[Book::PARAM_ISBN] ?? null,
            pages: $data[Book::PARAM_PAGES] ?? null,
            circulation: $data[Book::PARAM_CIRCULATION] ?? null,
            size: $data[Book::PARAM_SIZE] ?? null,
            livelibId: $data[Book::PARAM_LIVELIB_ID] ?? null,
            goodreadsId: $data[Book::PARAM_GOODREADS_ID] ?? null,
            fantlabId: $data[Book::PARAM_FANTLAB_ID] ?? null,
            livelibRating: $data[Book::PARAM_LIVELIB_RATING] ?? null,
            goodreadsRating: $data[Book::PARAM_GOODREADS_RATING] ?? null,
            publishingHouse: $data[Book::PARAM_PUBLISHING_HOUSE] ?? null,
            publishingBrand: $data[Book::PARAM_PUBLISHING_BRAND] ?? null,
            bookSeries: $data[Book::PARAM_BOOK_SERIES] ?? null,
            paperType: $data[Book::PARAM_PAPER_TYPE] ?? null,
            bookUserData: $data[Book::PARAM_BOOK_USER_DATA] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            Book::PARAM_TITLE => $this->title,
            Book::PARAM_ORIGINAL_TITLE => $this->originalTitle,
            Book::PARAM_ISBN => $this->isbn,
            Book::PARAM_PAGES => $this->pages,
            Book::PARAM_CIRCULATION => $this->circulation,
            Book::PARAM_SIZE => $this->size,
            Book::PARAM_PUBLISH_YEAR => $this->publishYear,
            Book::PARAM_LIVELIB_ID => $this->livelibId,
            Book::PARAM_GOODREADS_ID => $this->goodreadsId,
            Book::PARAM_FANTLAB_ID => $this->fantlabId,
            Book::PARAM_LIVELIB_RATING => $this->livelibRating,
            Book::PARAM_GOODREADS_RATING => $this->goodreadsRating,
            // > relations
            Book::PARAM_BINDING_TYPE => Arr::get($this->bindingType, 'id'),
            Book::PARAM_PAPER_TYPE => $this->paperType['id'] ?? null,
            Book::PARAM_PUBLISHING_HOUSE => $this->publishingHouse['id'] ?? null,
            Book::PARAM_PUBLISHING_BRAND => $this->publishingBrand['id'] ?? null,
            Book::PARAM_BOOK_SERIES => $this->bookSeries['id'] ?? null,
            Book::PARAM_AUTHORS => Arr::pluck($this->authors, 'id'),
            Book::PARAM_BOOK_USER_DATA => $this->bookUserData,
            // < relations
        ];
    }
}
