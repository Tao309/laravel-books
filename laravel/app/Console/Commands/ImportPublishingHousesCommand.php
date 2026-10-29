<?php

namespace App\Console\Commands;

use App\Models\PublishingHouse;
use App\Repositories\PublishingHouseRepository;
use Illuminate\Database\Eloquent\Model;

class ImportPublishingHousesCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Издательские дома';

    private const string FIELD_ID = 'id';

    private const string FIELD_NAME = 'name';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'publishing_houses.csv';

    protected ?string $seqTable = 'publishing_houses';

    protected $signature = 'app:import:publishing_houses';

    protected $description = 'Импортируем список издательских домов';

    public function __construct(
        private readonly PublishingHouseRepository $repository
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

    protected function createEntityByImportRowData(array $row): PublishingHouse
    {
        return $this->repository->create([
            PublishingHouse::ID => $row[self::FIELD_ID],
            PublishingHouse::NAME => $row[self::FIELD_NAME],
            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],
        ]);
    }
}
