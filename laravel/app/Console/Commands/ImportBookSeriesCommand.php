<?php

namespace App\Console\Commands;

use App\Models\BookSeries;
use App\Repositories\BookSeriesRepository;
use Illuminate\Database\Eloquent\Model;

class ImportBookSeriesCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Книжные серии';

    private const string FIELD_ID = 'id';

    private const string FIELD_NAME = 'name';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'book_series.csv';

    protected ?string $seqTable = BookSeries::TABLE_NAME;

    protected $signature = 'app:import:books_series';

    protected $description = 'Импортируем список книжных серий';

    public function __construct(
        private readonly BookSeriesRepository $repository
    ) {
        parent::__construct();
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

    protected function createEntityByImportRowData(array $row): BookSeries
    {
        return $this->repository->create([
            BookSeries::ID => $row[self::FIELD_ID],
            BookSeries::NAME => $row[self::FIELD_NAME],
            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],
        ]);
    }
}
