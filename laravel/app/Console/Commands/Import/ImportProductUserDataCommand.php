<?php

namespace App\Console\Commands\Import;

use App\Models\ProductUserData;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:product_user_data')]
class ImportProductUserDataCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'ProductUserData';

    private const string FIELD_PRODUCT_ID = 'product_id';

    private const string FIELD_USER_ID = 'user_id';

    public const string FIELD_AVAILABLE = 'available';

    public const string FIELD_NOT_AVAILABLE_DATE_FROM = 'not_available_date_from';

    public const string FIELD_AVAILABLE_DATE_FROM = 'available_date_from';

    public const string FIELD_LISTEN_PRICE_VALUE = 'listen_price_value';

    public const string FIELD_LISTEN_QTY_VALUE = 'listen_qty_value';

    public const string FIELD_RELEASE_DATE = 'release_date';

    public const string FIELD_IS_ARCHIVE = 'is_archive';

    public const string FIELD_COMMENT = 'comment';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'product_user_data.csv';

    protected ?string $tableName = ProductUserData::TABLE_NAME;

    protected $signature = 'app:import:product_user_data';

    protected $description = 'Импортируем список ProductUserData';

    protected int $batchSize = 200;

    protected bool $requireUpdateSequence = false;

    protected array $convertSchema = [
        self::FIELD_PRODUCT_ID => self::SCHEMA_FORCE_INTEGER,
        self::FIELD_USER_ID => self::SCHEMA_FORCE_INTEGER,
        self::FIELD_AVAILABLE => self::SCHEMA_BOOL,
        self::FIELD_LISTEN_PRICE_VALUE => self::SCHEMA_INTEGER,
        self::FIELD_LISTEN_QTY_VALUE => self::SCHEMA_INTEGER,
        self::FIELD_IS_ARCHIVE => self::SCHEMA_BOOL,
    ];

    protected function runBeforeImport(): void
    {
        ProductUserData::truncate();
    }

    protected function createInsertingData(array $row): array
    {
        return [
            ProductUserData::PARAM_PRODUCT_ID => $row[self::FIELD_PRODUCT_ID],
            ProductUserData::PARAM_USER_ID => $row[self::FIELD_USER_ID],

            ProductUserData::PARAM_AVAILABLE => $row[self::FIELD_AVAILABLE],
            ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM => $row[self::FIELD_NOT_AVAILABLE_DATE_FROM]
                ? $this->checkDateAndReturn(
                    $row[self::FIELD_PRODUCT_ID].'_'.$row[self::FIELD_USER_ID],
                    $row[self::FIELD_NOT_AVAILABLE_DATE_FROM]
                )
                : null,
            ProductUserData::PARAM_AVAILABLE_DATE_FROM => $row[self::FIELD_AVAILABLE_DATE_FROM]
                ? $this->checkDateAndReturn(
                    $row[self::FIELD_PRODUCT_ID].'_'.$row[self::FIELD_USER_ID],
                    $row[self::FIELD_AVAILABLE_DATE_FROM]
                )
                : null,
            ProductUserData::PARAM_LISTEN_PRICE_VALUE => $row[self::FIELD_LISTEN_PRICE_VALUE],
            ProductUserData::PARAM_LISTEN_QTY_VALUE => $row[self::FIELD_LISTEN_QTY_VALUE],
            ProductUserData::PARAM_RELEASE_DATE => $row[self::FIELD_RELEASE_DATE]
                ? $this->checkDateAndReturn(
                    $row[self::FIELD_PRODUCT_ID].'_'.$row[self::FIELD_USER_ID],
                    $row[self::FIELD_RELEASE_DATE]
                )
                : null,
            ProductUserData::PARAM_IS_ARCHIVE => $row[self::FIELD_IS_ARCHIVE],
            ProductUserData::PARAM_COMMENT => $row[self::FIELD_COMMENT],

            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],
        ];
    }
}
