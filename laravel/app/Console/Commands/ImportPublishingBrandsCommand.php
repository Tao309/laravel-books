<?php

namespace App\Console\Commands;

use App\Models\PublishingBrand;
use App\Repositories\PublishingBrandRepository;
use Illuminate\Database\Eloquent\Model;

class ImportPublishingBrandsCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Издательские брэнды';

    private const string FIELD_ID = 'id';

    private const string FIELD_NAME = 'name';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'publishing_brands.csv';

    protected ?string $seqTable = 'publishing_brands';

    protected $signature = 'app:import:publishing_brands';

    protected $description = 'Импортируем список издательских брэндов';

    public function __construct(
        private readonly PublishingBrandRepository $repository
    ) {
        parent::__construct();
    }

    protected function fillImportRow(array $row): void
    {
        $this->importData[] = [
            self::FIELD_ID => (int) $row[0],
            self::FIELD_NAME => $row[1],
            self::FIELD_DATE_UPDATED => $row[2],
            self::FIELD_DATE_CREATED => $row[3],
        ];
    }

    protected function createEntityByImportRowData(array $row): PublishingBrand
    {
        return $this->repository->create([
            PublishingBrand::ID => $row[self::FIELD_ID],
            PublishingBrand::NAME => $row[self::FIELD_NAME],
            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],
        ]);
    }
}
