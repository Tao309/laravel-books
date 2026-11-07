<?php

namespace App\Console\Commands\Import;

use App\Models\SourceProductUserData;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:source_product_user_data')]
class ImportSourceProductUserDataCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'SourceProductUserData';

    private const string FIELD_SOURCE_PRODUCT_ID = 'source_product_id';

    private const string FIELD_USER_ID = 'user_id';

    private const string FIELD_LISTEN_PRICE_VALUE = 'listen_price_value';

    private const string FIELD_COMMENT = 'comment';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'source_product_user_data.csv';

    protected ?string $tableName = SourceProductUserData::TABLE_NAME;

    protected $signature = 'app:import:source_product_user_data';

    protected $description = 'Импортируем список SourceProductUserData';

    protected int $batchSize = 200;

    protected bool $requireUpdateSequence = false;

    protected function runBeforeImport(): void
    {
        SourceProductUserData::truncate();
    }

    protected function fillImportRow(array $row): void
    {
        $this->importData[] = [
            self::FIELD_SOURCE_PRODUCT_ID => (int) $row[0],
            self::FIELD_USER_ID => (int) $row[1],
            self::FIELD_LISTEN_PRICE_VALUE => $row[2] ? (int) $row[2] : null,
            self::FIELD_COMMENT => $row[3],
            self::FIELD_DATE_UPDATED => $row[4],
            self::FIELD_DATE_CREATED => $row[5],
        ];
    }

    protected function createInsertingData(array $row): array
    {
        return [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $row[self::FIELD_SOURCE_PRODUCT_ID],
            SourceProductUserData::PARAM_USER_ID => $row[self::FIELD_USER_ID],
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => $row[self::FIELD_LISTEN_PRICE_VALUE],
            SourceProductUserData::PARAM_COMMENT => $row[self::FIELD_COMMENT],

            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],
        ];
    }
}
