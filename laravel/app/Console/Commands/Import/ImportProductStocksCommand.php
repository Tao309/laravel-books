<?php

namespace App\Console\Commands\Import;

use App\Models\ProductStock;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:product_stocks')]
class ImportProductStocksCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'ProductStock';

    private const string FIELD_PRODUCT_ID = 'id';

    private const string FIELD_USER_ID = 'user_id';

    private const string FIELD_QTY = 'qty';

    private const string FIELD_DATE = 'date';

    private const string FIELD_LOG = 'log';

    protected string $fileName = 'product_stocks.csv';

    protected ?string $tableName = ProductStock::TABLE_NAME;

    protected $signature = 'app:import:product_stocks';

    protected $description = 'Импортируем список стоков по товарам';

    // @todo верни 200
    protected int $batchSize = 1;

    protected bool $requireUpdateSequence = false;

    protected array $convertSchema = [
        self::FIELD_PRODUCT_ID => self::SCHEMA_FORCE_INTEGER,
        self::FIELD_USER_ID => self::SCHEMA_FORCE_INTEGER,
        self::FIELD_QTY => self::SCHEMA_INTEGER,
    ];

    protected function runBeforeImport(): void
    {
        ProductStock::truncate();
    }

    protected function fillImportRow(array $data): void
    {
        $data[self::FIELD_LOG] = json_encode(json_decode($data[self::FIELD_LOG], true));

        $this->importData[] = $data;
    }

    protected function createInsertingData(array $row): array
    {
        return [
            ProductStock::PARAM_PRODUCT_ID => $row[self::FIELD_PRODUCT_ID],
            ProductStock::PARAM_USER_ID => $row[self::FIELD_USER_ID],
            ProductStock::PARAM_QTY => $row[self::FIELD_QTY],
            ProductStock::PARAM_DATE => $row[self::FIELD_DATE]
                ? $this->checkDateAndReturn(
                    $row[self::FIELD_PRODUCT_ID].'_'.$row[self::FIELD_USER_ID],
                    $row[self::FIELD_DATE]
                )
                : null,
            ProductStock::PARAM_LOG => $row[self::FIELD_LOG],
        ];
    }
}
