<?php

namespace App\Console\Commands\Import;

use App\Models\Book;
use App\Models\Product;
use App\Models\SourceProduct;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:products')]
class ImportProductsCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Товары';

    private const string FIELD_ID = 'id';

    private const string PARAM_SHOP_PRODUCT_ID = 'shop_product_id';

    private const string PARAM_SHOP_PRODUCT_CODE = 'shop_product_code';

    private const string PARAM_SOURCE_PRODUCT_ID = 'source_product_id';

    private const string PARAM_BOOK_ID = 'book_id';

    private const string PARAM_SHOP_ID = 'shop_id';

    private const string PARAM_CITY_ID = 'city_id';

    private const string PARAM_TITLE = 'title';

    private const string FIELD_USER_CREATED_ID = 'user_created_id';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'products.csv';

    protected ?string $tableName = Product::TABLE_NAME;

    protected $signature = 'app:import:products';

    protected $description = 'Импортируем список товаров';

    protected array $convertSchema = [
        self::FIELD_ID => self::SCHEMA_FORCE_INTEGER,
        self::PARAM_SOURCE_PRODUCT_ID => self::SCHEMA_INTEGER,
        self::PARAM_BOOK_ID => self::SCHEMA_INTEGER,
        self::PARAM_SHOP_ID => self::SCHEMA_INTEGER,
        self::PARAM_CITY_ID => self::SCHEMA_INTEGER,
        self::FIELD_USER_CREATED_ID => self::SCHEMA_INTEGER,
    ];

    protected function runBeforeImport(): void
    {
        Product::truncate();
    }

    protected function createInsertingData(array $row): array
    {
        $sourceType = $sourceId = null;

        if (! empty($row[self::PARAM_BOOK_ID])) {
            $sourceType = Book::class;
            $sourceId = $row[self::PARAM_BOOK_ID];
        } elseif (! empty($row[self::PARAM_SOURCE_PRODUCT_ID])) {
            $sourceType = SourceProduct::class;
            $sourceId = $row[self::PARAM_SOURCE_PRODUCT_ID];
        }

        return [
            Product::PARAM_ID => $row[self::FIELD_ID],
            Product::PARAM_SHOP_PRODUCT_ID => $row[self::PARAM_SHOP_PRODUCT_ID],
            Product::PARAM_SHOP_PRODUCT_CODE => $row[self::PARAM_SHOP_PRODUCT_CODE],
            Product::PARAM_TITLE => $row[self::PARAM_TITLE],
            Model::UPDATED_AT => $this->checkDateAndReturn($row[self::FIELD_ID], $row[self::FIELD_DATE_UPDATED]),
            Model::CREATED_AT => $this->checkDateAndReturn($row[self::FIELD_ID], $row[self::FIELD_DATE_CREATED]),

            // > relations
            Product::PARAM_SOURCE_TYPE => $sourceType,
            Product::PARAM_SOURCE_ID => $sourceId,
            Product::PARAM_SHOP_ID => $row[self::PARAM_SHOP_ID],
            Product::PARAM_CITY_ID => $row[self::PARAM_CITY_ID],
            Product::PARAM_USER_CREATED_ID => $row[self::FIELD_USER_CREATED_ID],
            // < relations
        ];
    }
}
