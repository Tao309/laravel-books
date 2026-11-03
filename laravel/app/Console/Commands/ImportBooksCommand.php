<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Services\BookService;

class ImportBooksCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Книги';

    private const string FIELD_ID = 'id';

    private const string FIELD_TITLE = 'title';

    private const string FIELD_AUTHOR = 'author'; // Ее использовать

    private const string FIELD_ORIGINAL_TITLE = 'original_title';

    private const string FIELD_ORIGINAL_AUTHOR = 'original_author';

    private const string FIELD_ISBN = 'isbn';

    private const string FIELD_PAGES = 'pages';

    private const string FIELD_CIRCULATION = 'circulation';

    private const string FIELD_SIZE = 'size';

    private const string FIELD_BINDING_TYPE_ID = 'binding_type_id';

    private const string FIELD_PUBLISHING_HOUSE_ID = 'publishing_house_id';

    private const string FIELD_PUBLISHING_BRAND_ID = 'publishing_brand_id';

    private const string FIELD_BOOK_SERIES_ID = 'book_series_id';

    private const string FIELD_PUBLISH_YEAR = 'publish_year';

    private const string FIELD_LIVELIB_ID = 'livelib_id';

    private const string FIELD_GOODREADS_ID = 'goodreads_id';

    private const string FIELD_FANTLAB_ID = 'fantlab_id';

    private const string FIELD_LIVELIB_RATING = 'livelib_rating';

    private const string FIELD_GOODREADS_RATING = 'goodreads_rating';

    private const string FIELD_USER_CREATED_ID = 'user_created_id';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    private const string FIELD_BOOK_AUTHOR_ID = 'book_author_id';

    private const string FIELD_PAPER_TYPE_ID = 'paper_type_id';

    private const string FIELD_ON_SALE_COUNT = 'on_sale_count';

    protected string $fileName = 'books.csv';

    protected ?string $seqTable = Book::TABLE_NAME;

    protected $signature = 'app:import:Seeder';

    protected $description = 'Импортируем список книг';

    public function __construct(
        private readonly BookService $bookService,
    ) {
        parent::__construct();
    }

    protected function fillImportRow(array $row): void
    {
        $this->importData[] = [
            self::FIELD_ID => (int) $row[0],
            self::FIELD_TITLE => $row[1],
            self::FIELD_AUTHOR => trim($row[2]),
            self::FIELD_ORIGINAL_TITLE => $row[3],
            self::FIELD_ORIGINAL_AUTHOR => $row[4],
            self::FIELD_ISBN => $row[5] ?? '-',
            self::FIELD_PAGES => $row[6] ? (int) $row[6] : null,
            self::FIELD_CIRCULATION => $row[7] ? (int) $row[7] : null,
            self::FIELD_SIZE => $row[8],
            self::FIELD_BINDING_TYPE_ID => (int) $row[9],
            self::FIELD_PUBLISHING_HOUSE_ID => $row[10],
            self::FIELD_PUBLISHING_BRAND_ID => $row[11],
            self::FIELD_BOOK_SERIES_ID => $row[12],
            self::FIELD_PUBLISH_YEAR => $row[13] ? (int) $row[13] : null,
            self::FIELD_LIVELIB_ID => $row[14],
            self::FIELD_GOODREADS_ID => $row[15],
            self::FIELD_FANTLAB_ID => $row[16],
            self::FIELD_LIVELIB_RATING => $row[17] ? (float) $row[17] : null,
            self::FIELD_GOODREADS_RATING => $row[18] ? (float) $row[18] : null,
            self::FIELD_USER_CREATED_ID => $row[19],
            self::FIELD_DATE_UPDATED => $row[20],
            self::FIELD_DATE_CREATED => $row[21],
            self::FIELD_BOOK_AUTHOR_ID => (int) $row[22],
        ];
    }

    protected function runBeforeImport(): void
    {
        Book::unguard();
    }

    protected function createEntityByImportRowData(array $row): mixed
    {
        $entity = (new Book)
            ->setId($row[self::FIELD_ID])
            ->setTitle($row[self::FIELD_TITLE])
            ->setOriginalTitle($row[self::FIELD_ORIGINAL_TITLE])
            ->setIsbn($row[self::FIELD_ISBN])
            ->setPages($row[self::FIELD_PAGES])
            ->setCirculation($row[self::FIELD_CIRCULATION])
            ->setSize($row[self::FIELD_SIZE])
            ->setPublishYear($row[self::FIELD_PUBLISH_YEAR])
            ->setLivelibId($row[self::FIELD_LIVELIB_ID])
            ->setGoodreadsId($row[self::FIELD_GOODREADS_ID])
            ->setFantlabId($row[self::FIELD_FANTLAB_ID])
            ->setLivelibRating($row[self::FIELD_LIVELIB_RATING])
            ->setGoodreadsRating($row[self::FIELD_GOODREADS_RATING])
            ->setDateUpdated(new \DateTime($row[self::FIELD_DATE_UPDATED]))
            ->setDateCreated(new \DateTime($row[self::FIELD_DATE_CREATED]));

        $this->setAuthor($entity, $row[self::FIELD_BOOK_AUTHOR_ID]);
        $this->setBindingType($entity, $row[self::FIELD_BINDING_TYPE_ID]);
        $this->setPublishingHouse($entity, $row[self::FIELD_PUBLISHING_HOUSE_ID]);
        $this->setPublishingBrand($entity, $row[self::FIELD_PUBLISHING_BRAND_ID]);
        $this->setBookSeries($entity, $row[self::FIELD_BOOK_SERIES_ID]);
        $this->setUser($entity, $row[self::FIELD_AUTHOR_USER_ID]);

        return $entity;
    }

    private function setAuthor(Book $book, int $bookAuthorId): void
    {
        if (empty($bookAuthorId)) {
            throw new \RuntimeException('BookAuthor cannot be empty');
        }

        $foundBookAuthor = $this->bookAuthorRepository->find($bookAuthorId);

        if (empty($foundBookAuthor)) {
            throw new \RuntimeException(\sprintf('BookAuthor %s is not found in repository', $bookAuthorId));
        }

        $book->setBookAuthor($foundBookAuthor);
    }

    private function setBindingType(Book $book, int $bindingTypeId): void
    {
        if (empty($bindingTypeId)) {
            throw new \RuntimeException('BindingType cannot be empty');
        }

        $foundBindingType = $this->bookBindingTypeRepository->find($bindingTypeId);

        if (empty($foundBindingType)) {
            throw new \RuntimeException(\sprintf('BindingType %s is not found in repository', $bindingTypeId));
        }

        $book->setBindingType($foundBindingType);
    }

    private function setPublishingHouse(Book $book, ?string $publishingHouseId = null): void
    {
        if (empty($publishingHouseId)) {
            return;
        }

        $publishingHouse = $this->bookPublishingHouseRepository->find($publishingHouseId);

        if (empty($publishingHouse)) {
            throw new \RuntimeException(\sprintf('PublishingHouse %s is not found in repository', $publishingHouseId));
        }

        $book->setPublishingHouse($publishingHouse);
    }

    private function setPublishingBrand(Book $book, ?string $publishingBrandId = null): void
    {
        if (empty($publishingBrandId)) {
            return;
        }

        $publishingBrand = $this->bookPublishingBrandRepository->find($publishingBrandId);

        if (empty($publishingBrand)) {
            throw new \RuntimeException(\sprintf('PublishingBrand %s is not found in repository', $publishingBrandId));
        }

        $book->setPublishingBrand($publishingBrand);
    }

    private function setBookSeries(Book $book, ?string $bookSeriesId = null): void
    {
        if (empty($bookSeriesId)) {
            return;
        }

        $bookSeries = $this->bookSeriesRepository->find($bookSeriesId);

        if (empty($bookSeries)) {
            throw new \RuntimeException(\sprintf('BookSeries %s is not found in repository', $bookSeriesId));
        }

        $book->setBookSeries($bookSeries);
    }

    private function setUser(Book $book, string $userId): void
    {
        if (empty($userId)) {
            throw new \RuntimeException('User cannot be empty');
        }

        $user = $this->userRepository->find($userId);

        if (empty($user)) {
            throw new \RuntimeException(\sprintf('User %s is not found in repository', $userId));
        }

        $book->setUserCreated($user);
    }
}
