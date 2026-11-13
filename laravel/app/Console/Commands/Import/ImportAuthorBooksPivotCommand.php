<?php

namespace App\Console\Commands\Import;

use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:author_books')]
class ImportAuthorBooksPivotCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Связи автор и книга';

    private const string FIELD_BOOK_ID = 'book_id';

    private const string FIELD_AUTHOR_ID = 'author_id';

    protected string $fileName = 'author_books.csv';

    protected ?string $tableName = Author::PIVOT_AUTHOR_BOOKS_TABLE;

    protected $signature = 'app:import:author_books';

    protected $description = 'Импортируем список связей автора и книги';

    protected array $convertSchema = [
        self::FIELD_BOOK_ID => self::SCHEMA_FORCE_INTEGER,
        self::FIELD_AUTHOR_ID => self::SCHEMA_FORCE_INTEGER,
    ];

    protected function runBeforeImport(): void
    {
        DB::table(Author::PIVOT_AUTHOR_BOOKS_TABLE)->truncate();
    }

    protected function createInsertingData(array $row): array
    {
        return [
            Author::PIVOT_AUTHOR_BOOKS_AUTHOR_ID => $row[self::FIELD_AUTHOR_ID],
            Author::PIVOT_AUTHOR_BOOKS_BOOK_ID => $row[self::FIELD_BOOK_ID],
            Model::UPDATED_AT => Carbon::now(),
            Model::CREATED_AT => Carbon::now(),
        ];
    }
}
