<?php

namespace App\Console\Commands\Import;

use App\Models\PublishingHouse;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:publishing_houses')]
class ImportPublishingHousesCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Издательские дома';

    private const string FIELD_ID = 'id';

    private const string FIELD_NAME = 'name';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'publishing_houses.csv';

    protected ?string $tableName = PublishingHouse::TABLE_NAME;

    protected $signature = 'app:import:publishing_houses';

    protected $description = 'Импортируем список издательских домов';

    protected array $convertSchema = [
        self::FIELD_ID => self::SCHEMA_FORCE_INTEGER,
    ];

    protected function runBeforeImport(): void
    {
        PublishingHouse::truncate();
    }

    protected function createInsertingData(array $row): array
    {
        return [
            PublishingHouse::PARAM_ID => $row[self::FIELD_ID],
            PublishingHouse::PARAM_NAME => $row[self::FIELD_NAME],
            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],
        ];
    }
}
