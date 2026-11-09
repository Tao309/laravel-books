<?php

namespace App\Console\Commands\Import;

use App\Models\PublishingBrand;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:publishing_brands')]
class ImportPublishingBrandsCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Издательские брэнды';

    private const string FIELD_ID = 'id';

    private const string FIELD_NAME = 'name';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'publishing_brands.csv';

    protected ?string $tableName = PublishingBrand::TABLE_NAME;

    protected $signature = 'app:import:publishing_brands';

    protected $description = 'Импортируем список издательских брэндов';

    protected array $convertSchema = [
        self::FIELD_ID => self::SCHEMA_FORCE_INTEGER,
    ];

    protected function runBeforeImport(): void
    {
        PublishingBrand::truncate();
    }

    protected function createInsertingData(array $row): array
    {
        return [
            PublishingBrand::PARAM_ID => $row[self::FIELD_ID],
            PublishingBrand::PARAM_NAME => $row[self::FIELD_NAME],
            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],
        ];
    }
}
