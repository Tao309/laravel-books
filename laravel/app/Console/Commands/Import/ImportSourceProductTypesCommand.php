<?php

namespace App\Console\Commands\Import;

use App\Models\SourceProductType;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:source_product_types')]
class ImportSourceProductTypesCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Типы источников товаров';

    private const string FIELD_ID = 'id';

    private const string FIELD_CODE = 'code';

    private const string FIELD_NAME = 'name';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'source_product_types.csv';

    protected ?string $tableName = SourceProductType::TABLE_NAME;

    protected $signature = 'app:import:source_product_types';

    protected $description = 'Импортируем список типов источников товаров';

    protected function runBeforeImport(): void
    {
        SourceProductType::truncate();
    }

    protected function fillImportRow(array $row): void
    {
        $this->importData[] = [
            self::FIELD_ID => (int) $row[0],
            self::FIELD_CODE => $row[1],
            self::FIELD_NAME => $row[2],
            self::FIELD_DATE_UPDATED => $row[3],
            self::FIELD_DATE_CREATED => $row[3],
        ];
    }

    protected function createInsertingData(array $row): array
    {
        return [
            SourceProductType::PARAM_ID => $row[self::FIELD_ID],
            SourceProductType::PARAM_CODE => $row[self::FIELD_CODE],
            SourceProductType::PARAM_NAME => $row[self::FIELD_NAME],
            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],
        ];
    }
}
