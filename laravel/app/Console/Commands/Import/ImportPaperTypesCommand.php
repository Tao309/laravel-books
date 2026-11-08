<?php

namespace App\Console\Commands\Import;

use App\Models\PaperType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:paper_types')]
class ImportPaperTypesCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'Типы бумаг';

    private const string FIELD_ID = 'id';

    private const string FIELD_NAME = 'label';

    private const string FIELD_DESCRIPTION = 'description';

    protected string $fileName = 'paper_types.csv';

    protected ?string $tableName = PaperType::TABLE_NAME;

    protected $signature = 'app:import:paper_types';

    protected $description = 'Импортируем список типов бумаги';

    protected array $convertSchema = [
        self::FIELD_ID => self::SCHEMA_FORCE_INTEGER,
    ];

    protected function runBeforeImport(): void
    {
        PaperType::truncate();
    }

    protected function createInsertingData(array $row): array
    {
        return [
            PaperType::PARAM_ID => $row[self::FIELD_ID],
            PaperType::PARAM_LABEL => $row[self::FIELD_NAME],
            PaperType::PARAM_DESCRIPTION => $row[self::FIELD_DESCRIPTION],
            Model::UPDATED_AT => Carbon::now(),
            Model::CREATED_AT => Carbon::now(),
        ];
    }
}
