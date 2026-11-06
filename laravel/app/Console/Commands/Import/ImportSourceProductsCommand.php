<?php

namespace App\Console\Commands\Import;

use App\Models\SourceProduct;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:source_products')]
class ImportSourceProductsCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Источники товаров';

    private const string FIELD_ID = 'id';

    private const string FIELD_SOURCE_PRODUCT_ID = 'source_product_id';

    private const string FIELD_TITLE = 'title';

    private const string FIELD_USER_CREATED_ID = 'user_created_id';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'source_products.csv';

    protected ?string $tableName = SourceProduct::TABLE_NAME;

    protected $signature = 'app:import:source_products';

    protected $description = 'Импортируем список источников товаров';

    protected function runBeforeImport(): void
    {
        SourceProduct::truncate();
    }

    protected function fillImportRow(array $row): void
    {
        $this->importData[] = [
            self::FIELD_ID => (int) $row[0],
            self::FIELD_SOURCE_PRODUCT_ID => (int) $row[1],
            self::FIELD_TITLE => $row[2],
            self::FIELD_USER_CREATED_ID => (int) $row[3],
            self::FIELD_DATE_UPDATED => $row[4],
            self::FIELD_DATE_CREATED => $row[5],
        ];
    }

    protected function createInsertingData(array $row): array
    {
        return [
            SourceProduct::PARAM_ID => $row[self::FIELD_ID],
            SourceProduct::PARAM_TITLE => $row[self::FIELD_TITLE],
            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],

            // > relations
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $row[self::FIELD_SOURCE_PRODUCT_ID],
            SourceProduct::PARAM_USER_CREATED_ID => $row[self::FIELD_USER_CREATED_ID],
            // < relations
        ];
    }
}
