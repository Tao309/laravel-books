<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Database\Eloquent\Model;

class ImportBooksCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Книги';

    private const string FIELD_ID = 'id';

    private const string FIELD_TITLE = 'title';

    private const string FIELD_AUTHOR = 'author'; // Пропустить

    private const string FIELD_ORIGINAL_TITLE = 'original_title';

    private const string FIELD_ORIGINAL_AUTHOR = 'original_author'; // Пропустить

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

    protected $signature = 'app:import:books';

    protected $description = 'Импортируем список книг';

    public function __construct(
        private readonly BookRepository $bookRepository,
    ) {
        parent::__construct();
    }

    protected function fillImportRow(array $row): void
    {
        $this->importData[] = [
            self::FIELD_ID => (int) $row[0],
            self::FIELD_TITLE => $row[1],
            //            self::FIELD_AUTHOR => trim($row[2]), // Пропустить
            self::FIELD_ORIGINAL_TITLE => $row[3],
            //            self::FIELD_ORIGINAL_AUTHOR => $row[4], // Пропустить
            self::FIELD_ISBN => $row[5] ?? '-',
            self::FIELD_PAGES => $row[6] ? (int) $row[6] : null,
            self::FIELD_CIRCULATION => $row[7] ? (int) $row[7] : null,
            self::FIELD_SIZE => $row[8],
            self::FIELD_BINDING_TYPE_ID => $row[9] ?? (int) $row[9],
            self::FIELD_PUBLISHING_HOUSE_ID => $row[10] ? (int) $row[10] : null,
            self::FIELD_PUBLISHING_BRAND_ID => $row[11] ? (int) $row[11] : null,
            self::FIELD_BOOK_SERIES_ID => $row[12] ? (int) $row[12] : null,
            self::FIELD_PUBLISH_YEAR => $row[13] ? (int) $row[13] : null,
            self::FIELD_LIVELIB_ID => $row[14],
            self::FIELD_GOODREADS_ID => $row[15],
            self::FIELD_FANTLAB_ID => $row[16],
            self::FIELD_LIVELIB_RATING => $row[17] ? (float) $row[17] : null,
            self::FIELD_GOODREADS_RATING => $row[18] ? (float) $row[18] : null,
            self::FIELD_USER_CREATED_ID => (int) $row[19],
            self::FIELD_DATE_UPDATED => $row[20],
            self::FIELD_DATE_CREATED => $row[21],
            self::FIELD_BOOK_AUTHOR_ID => (int) $row[22],
            self::FIELD_PAPER_TYPE_ID => $row[23],
            self::FIELD_ON_SALE_COUNT => $row[24] ? (int) $row[24] : null,
        ];
    }

    protected function createEntityByImportRowData(array $row): Book
    {
        $data = [
            Book::PARAM_ID => $row[self::FIELD_ID],
            Book::PARAM_TITLE => $row[self::FIELD_TITLE],
            Book::PARAM_ORIGINAL_TITLE => $row[self::FIELD_ORIGINAL_TITLE],
            Book::PARAM_ISBN => $row[self::FIELD_ISBN],
            Book::PARAM_PAGES => $row[self::FIELD_PAGES],
            Book::PARAM_CIRCULATION => $row[self::FIELD_CIRCULATION],
            Book::PARAM_SIZE => $row[self::FIELD_SIZE],
            Book::PARAM_PUBLISH_YEAR => $row[self::FIELD_PUBLISH_YEAR],
            Book::PARAM_LIVELIB_ID => $row[self::FIELD_LIVELIB_ID],
            Book::PARAM_GOODREADS_ID => $row[self::FIELD_GOODREADS_ID],
            Book::PARAM_FANTLAB_ID => $row[self::FIELD_FANTLAB_ID],
            Book::PARAM_LIVELIB_RATING => $row[self::FIELD_LIVELIB_RATING],
            Book::PARAM_GOODREADS_RATING => $row[self::FIELD_GOODREADS_RATING],
            Model::UPDATED_AT => $this->checkDateAndReturn($row[self::FIELD_DATE_UPDATED]),
            Model::CREATED_AT => $this->checkDateAndReturn($row[self::FIELD_DATE_CREATED]),

            Book::PARAM_BINDING_TYPE => $row[self::FIELD_BINDING_TYPE_ID],
            Book::PARAM_PAPER_TYPE => $row[self::FIELD_PAPER_TYPE_ID],
            Book::PARAM_PUBLISHING_HOUSE => $row[self::FIELD_PUBLISHING_HOUSE_ID],
            Book::PARAM_PUBLISHING_BRAND => $row[self::FIELD_PUBLISHING_BRAND_ID],
            Book::PARAM_BOOK_SERIES => $row[self::FIELD_PUBLISHING_BRAND_ID],
            Book::PARAM_AUTHORS => [$row[self::FIELD_BOOK_AUTHOR_ID]],

            // @todo записать реального пользователя
            //            Book::PARAM_USER_CREATED => ['id' => 1],
        ];

        return $this->bookRepository->create($data);
    }
}
