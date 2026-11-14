<?php

namespace App\DTOs;

use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\PaperType;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use Illuminate\Support\Arr;

class BookDTO extends AbstractDTO
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
            originalTitle: Arr::get($data, Book::PARAM_ORIGINAL_TITLE),
            isbn: Arr::get($data, Book::PARAM_ISBN),
            pages: Arr::get($data, Book::PARAM_PAGES),
            circulation: Arr::get($data, Book::PARAM_CIRCULATION),
            size: Arr::get($data, Book::PARAM_SIZE),
            livelibId: Arr::get($data, Book::PARAM_LIVELIB_ID),
            goodreadsId: Arr::get($data, Book::PARAM_GOODREADS_ID),
            fantlabId: Arr::get($data, Book::PARAM_FANTLAB_ID),
            livelibRating: Arr::get($data, Book::PARAM_LIVELIB_RATING),
            goodreadsRating: Arr::get($data, Book::PARAM_GOODREADS_RATING),
            publishingHouse: Arr::get($data, Book::PARAM_PUBLISHING_HOUSE),
            publishingBrand: Arr::get($data, Book::PARAM_PUBLISHING_BRAND),
            bookSeries: Arr::get($data, Book::PARAM_BOOK_SERIES),
            paperType: Arr::get($data, Book::PARAM_PAPER_TYPE),
            bookUserData: $data[Book::PARAM_BOOK_USER_DATA] ?? [],
        );
    }

    public function getEntityData(): array
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
        ];
    }

    public function getRelationsData(): array
    {
        return [
            Book::PARAM_BINDING_TYPE => Arr::get($this->bindingType, BindingType::PARAM_ID),
            Book::PARAM_PAPER_TYPE => Arr::get($this->paperType, PaperType::PARAM_ID),
            Book::PARAM_PUBLISHING_HOUSE => Arr::get($this->publishingHouse, PublishingHouse::PARAM_ID),
            Book::PARAM_PUBLISHING_BRAND => Arr::get($this->publishingBrand, PublishingBrand::PARAM_ID),
            Book::PARAM_BOOK_SERIES => Arr::get($this->bookSeries, BookSeries::PARAM_ID),
            Book::PARAM_AUTHORS => Arr::pluck($this->authors, Author::PARAM_ID),
            Book::PARAM_BOOK_USER_DATA => $this->bookUserData,
        ];
    }
}
