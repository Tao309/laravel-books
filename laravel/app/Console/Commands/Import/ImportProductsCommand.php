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

    protected function runBeforeImport(): void
    {
        Product::truncate();
    }

    protected function fillImportRow(array $row): void
    {
        $this->importData[] = [
            self::FIELD_ID => (int) $row[0],
            self::PARAM_SHOP_PRODUCT_ID => $row[1],
            self::PARAM_SHOP_PRODUCT_CODE => $row[2] ?? null,
            self::PARAM_SOURCE_PRODUCT_ID => $row[3] ? (int) $row[3] : null,
            self::PARAM_BOOK_ID => $row[4] ? (int) $row[4] : null,
            self::PARAM_SHOP_ID => $row[5] ? (int) $row[5] : null,
            self::PARAM_CITY_ID => $row[6] ? (int) $row[6] : null,
            self::PARAM_TITLE => $row[7],
            self::FIELD_USER_CREATED_ID => $row[8] ? (int) $row[8] : null,
            self::FIELD_DATE_UPDATED => $row[9],
            self::FIELD_DATE_CREATED => $row[10],
        ];
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
