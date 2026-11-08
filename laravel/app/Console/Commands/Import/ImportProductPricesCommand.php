<?php

namespace App\Console\Commands\Import;

use App\Models\ProductPrice;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:product_prices')]
class ImportProductPricesCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'ProductPrice';

    private const string FIELD_PRODUCT_ID = 'id';

    private const string FIELD_USER_ID = 'user_id';

    private const string FIELD_PRICE = 'price';

    private const string FIELD_DATE = 'date';

    protected string $fileName = 'product_prices.csv';

    protected ?string $tableName = ProductPrice::TABLE_NAME;

    protected $signature = 'app:import:product_prices';

    protected $description = 'Импортируем список цен по товарам';

    protected int $batchSize = 200;

    protected bool $requireUpdateSequence = false;

    protected array $convertSchema = [
        self::FIELD_PRODUCT_ID => self::SCHEMA_FORCE_INTEGER,
        self::FIELD_USER_ID => self::SCHEMA_FORCE_INTEGER,
        self::FIELD_PRICE => self::SCHEMA_INTEGER,
    ];

    protected function runBeforeImport(): void
    {
        ProductPrice::truncate();
    }

    protected function createInsertingData(array $row): array
    {
        return [
            ProductPrice::PARAM_PRODUCT_ID => $row[self::FIELD_PRODUCT_ID],
            ProductPrice::PARAM_USER_ID => $row[self::FIELD_USER_ID],
            ProductPrice::PARAM_PRICE => $row[self::FIELD_PRICE],
            ProductPrice::PARAM_DATE => $row[self::FIELD_DATE]
                ? $this->checkDateAndReturn(
                    $row[self::FIELD_PRODUCT_ID].'_'.$row[self::FIELD_USER_ID],
                    $row[self::FIELD_DATE]
                )
                : null,
        ];
    }
}
