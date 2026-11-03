<?php

namespace App\Console\Commands;

use App\Models\PaperType;
use App\Repositories\PaperTypeRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ImportPaperTypesCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Типы бумаг';

    private const string FIELD_ID = 'id';

    private const string FIELD_NAME = 'label';

    private const string FIELD_DESCRIPTION = 'description';

    protected string $fileName = 'paper_types.csv';

    protected ?string $seqTable = 'paper_types';

    protected $signature = 'app:import:paper_types';

    protected $description = 'Импортируем список типов бумаги';

    public function __construct(
        private readonly PaperTypeRepository $repository
    ) {
        parent::__construct();
    }

    protected function runBeforeImport(): void
    {
        PaperType::unguard();
    }

    protected function fillImportRow(array $row): void
    {
        $this->importData[] = [
            self::FIELD_ID => (int) $row[0],
            self::FIELD_NAME => $row[1],
            self::FIELD_DESCRIPTION => $row[2],
        ];
    }

    protected function createEntityByImportRowData(array $row): PaperType
    {
        return $this->repository->create([
            PaperType::PARAM_ID => $row[self::FIELD_ID],
            PaperType::PARAM_LABEL => $row[self::FIELD_NAME],
            PaperType::PARAM_DESCRIPTION => $row[self::FIELD_DESCRIPTION],
            Model::UPDATED_AT => Carbon::now(),
            Model::CREATED_AT => Carbon::now(),
        ]);
    }
}
