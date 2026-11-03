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
        public array $publishingHouse,
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
        public ?array $publishingBrand = null,
        public ?array $bookSeries = null,
        public ?array $paperType = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data[Book::PARAM_TITLE],
            publishYear: $data[Book::PARAM_PUBLISH_YEAR],
            bindingType: $data[Book::PARAM_BINDING_TYPE],
            authors: $data[Book::PARAM_AUTHORS],
            publishingHouse: $data[Book::PARAM_PUBLISHING_HOUSE],
            originalTitle: $data[Book::PARAM_ORIGINAL_TITLE],
            isbn: $data[Book::PARAM_ISBN],
            pages: $data[Book::PARAM_PAGES],
            circulation: $data[Book::PARAM_CIRCULATION],
            size: $data[Book::PARAM_SIZE],
            livelibId: $data[Book::PARAM_LIVELIB_ID],
            goodreadsId: $data[Book::PARAM_GOODREADS_ID],
            fantlabId: $data[Book::PARAM_FANTLAB_ID],
            livelibRating: $data[Book::PARAM_LIVELIB_RATING],
            goodreadsRating: $data[Book::PARAM_GOODREADS_RATING],
            publishingBrand: $data[Book::PARAM_PUBLISHING_BRAND],
            bookSeries: $data[Book::PARAM_BOOK_SERIES],
            paperType: $data[Book::PARAM_PAPER_TYPE],
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
            Book::PARAM_PAPER_TYPE => Arr::get($this->paperType, 'id'),
            Book::PARAM_PUBLISHING_HOUSE => Arr::get($this->publishingHouse, 'id'),
            Book::PARAM_PUBLISHING_BRAND => Arr::get($this->publishingBrand, 'id'),
            Book::PARAM_BOOK_SERIES => Arr::get($this->bookSeries, 'id'),
            Book::PARAM_AUTHORS => Arr::pluck($this->authors, 'id'),
            // < relations
        ];
    }
}
