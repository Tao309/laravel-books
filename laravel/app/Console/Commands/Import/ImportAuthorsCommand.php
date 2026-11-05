<?php

namespace App\Console\Commands\Import;

use App\Models\Author;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:authors')]
class ImportAuthorsCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Авторы';

    private const string FIELD_ID = 'id';

    private const string FIELD_FIRST_NAME = 'first_name';

    private const string FIELD_LAST_NAME = 'last_name';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'authors.csv';

    protected ?string $tableName = Author::TABLE_NAME;

    protected $signature = 'app:import:authors';

    protected $description = 'Импортируем список авторов';

    protected function runBeforeImport(): void
    {
        Author::truncate();
    }

    protected function fillImportRow(array $row): void
    {
        $this->importData[] = [
            self::FIELD_ID => (int) $row[0],
            self::FIELD_FIRST_NAME => $row[1],
            self::FIELD_LAST_NAME => $row[2],
            self::FIELD_DATE_UPDATED => $row[3],
            self::FIELD_DATE_CREATED => $row[4],
        ];
    }

    protected function createInsertingData(array $row): array
    {
        return [
            Author::PARAM_ID => $row[self::FIELD_ID],
            Author::PARAM_FIRST_NAME => $row[self::FIELD_FIRST_NAME],
            Author::PARAM_LAST_NAME => $row[self::FIELD_LAST_NAME],
            Model::UPDATED_AT => $this->checkDateAndReturn($row[self::FIELD_ID], $row[self::FIELD_DATE_UPDATED]),
            Model::CREATED_AT => $this->checkDateAndReturn($row[self::FIELD_ID], $row[self::FIELD_DATE_CREATED]),
        ];
    }
}
