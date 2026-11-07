<?php

namespace App\Console\Commands\Import;

use App\Models\BookSeries;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:books_series')]
class ImportBookSeriesCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Книжные серии';

    private const string FIELD_ID = 'id';

    private const string FIELD_NAME = 'name';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'book_series.csv';

    protected ?string $tableName = BookSeries::TABLE_NAME;

    protected $signature = 'app:import:books_series';

    protected $description = 'Импортируем список книжных серий';

    protected function runBeforeImport(): void
    {
        BookSeries::truncate();
    }

    protected function fillImportRow(array $row): void
    {
        $this->importData[] = [
            self::FIELD_ID => (int) $row[0],
            self::FIELD_NAME => $row[1],
            self::FIELD_DATE_UPDATED => $row[2],
            self::FIELD_DATE_CREATED => $row[3],
        ];
    }

    protected function createInsertingData(array $row): array
    {
        return [
            BookSeries::PARAM_ID => $row[self::FIELD_ID],
            BookSeries::PARAM_NAME => $row[self::FIELD_NAME],
            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],
        ];
    }
}
