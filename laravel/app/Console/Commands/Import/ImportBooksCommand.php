<?php

namespace App\Console\Commands\Import;

use App\Models\Author;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:books')]
class ImportBooksCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Книги';

    private const string FIELD_ID = 'id';

    private const string FIELD_TITLE = 'title';

    private const string FIELD_ORIGINAL_TITLE = 'original_title';

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

    protected ?string $tableName = Book::TABLE_NAME;

    protected $signature = 'app:import:books';

    protected $description = 'Импортируем список книг';

    private array $authorData = [];

    private array $insertBulkAuthorData = [];

    protected array $convertSchema = [
        self::FIELD_ID => self::SCHEMA_FORCE_INTEGER,
        self::FIELD_PAGES => self::SCHEMA_INTEGER,
        self::FIELD_CIRCULATION => self::SCHEMA_INTEGER,
        self::FIELD_BINDING_TYPE_ID => self::SCHEMA_INTEGER,
        self::FIELD_PUBLISHING_HOUSE_ID => self::SCHEMA_INTEGER,
        self::FIELD_PUBLISHING_BRAND_ID => self::SCHEMA_INTEGER,
        self::FIELD_BOOK_SERIES_ID => self::SCHEMA_INTEGER,
    ];

    protected function runBeforeImport(): void
    {
        Book::truncate();
    }

    protected function fillImportRow(array $data): void
    {
        $data[self::FIELD_ISBN] = $data[self::FIELD_ISBN] ?? '-';

        $this->importData[] = $data;
    }

    protected function createInsertingData(array $row): array
    {
        $this->authorData[] = [
            Author::PIVOT_AUTHOR_BOOKS_AUTHOR_ID => $row[self::FIELD_BOOK_AUTHOR_ID],
            Author::PIVOT_AUTHOR_BOOKS_BOOK_ID => $row[self::FIELD_ID],
            Model::UPDATED_AT => Carbon::now(),
            Model::CREATED_AT => Carbon::now(),
        ];

        return [
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
            Book::PARAM_ON_SALE_COUNT => $row[self::FIELD_ON_SALE_COUNT],
            Model::UPDATED_AT => $this->checkDateAndReturn($row[self::FIELD_ID], $row[self::FIELD_DATE_UPDATED]),
            Model::CREATED_AT => $this->checkDateAndReturn($row[self::FIELD_ID], $row[self::FIELD_DATE_CREATED]),

            // > relations
            Book::PARAM_BINDING_TYPE_ID => $row[self::FIELD_BINDING_TYPE_ID],
            Book::PARAM_PAPER_TYPE_ID => $row[self::FIELD_PAPER_TYPE_ID],
            Book::PARAM_PUBLISHING_HOUSE_ID => $row[self::FIELD_PUBLISHING_HOUSE_ID],
            Book::PARAM_PUBLISHING_BRAND_ID => $row[self::FIELD_PUBLISHING_BRAND_ID],
            Book::PARAM_BOOK_SERIES_ID => $row[self::FIELD_PUBLISHING_BRAND_ID],
            Book::PARAM_USER_CREATED_ID => $row[self::FIELD_USER_CREATED_ID],
            // < relations
        ];
    }

    protected function runAfterImport(): void
    {
        if ($this->justCheckParsing) {
            return;
        }

        $this->io->title('Авторы по книгам => Запись в БД...');

        $batchSize = 500;
        $totalRecords = \count($this->authorData);

        $this->io->progressStart($totalRecords);

        if (! $this->isFake) {
            DB::beginTransaction();
        }

        $added = 0;

        try {
            for ($i = 0; $i < $totalRecords; $i++) {
                try {
                    $importData = $this->authorData[$i];

                    $this->insertBulkAuthorData[] = $importData;

                    if (($i % $batchSize) === 0) {
                        if (! $this->isFake) {
                            $this->applyInsertBulkAuthorData();
                        }
                    }

                    $added++;
                    $this->io->progressAdvance();
                } catch (\Throwable $e) {
                    $this->io->error(json_encode($importData, \JSON_PRETTY_PRINT));

                    throw $e;
                }
            }

            if (! $this->isFake) {
                $this->applyInsertBulkAuthorData();

                DB::commit();
            }
        } catch (\Throwable $e) {
            if (! $this->isFake) {
                DB::rollBack();
            }

            Log::error($e);

            throw new \RuntimeException('Ошибка при записи "'.static::COMMAND_LABEL.'" в БД: '.$e->getMessage());
        }

        $this->io->progressFinish();

        $this->io->title('Завершение импорта: Авторы по книгам');
        $this->io->section('Добавлено: '.$added);
    }

    private function applyInsertBulkAuthorData(): void
    {
        DB::table(Author::PIVOT_AUTHOR_BOOKS_TABLE)->insert($this->insertBulkAuthorData);

        $this->insertBulkAuthorData = [];
    }
}
