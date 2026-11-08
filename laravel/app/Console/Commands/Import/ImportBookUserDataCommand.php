<?php

namespace App\Console\Commands\Import;

use App\Models\BookUserData;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:import:book_user_data')]
class ImportBookUserDataCommand extends CommonImportCommand
{
    public const string COMMAND_LABEL = 'BookUserData';

    private const string FIELD_BOOK_ID = 'book_id';

    private const string FIELD_USER_ID = 'user_id';

    private const string FIELD_RELEASE_DATE = 'release_date';

    private const string FIELD_LISTEN_PRICE_VALUE = 'listen_price_value';

    private const string FIELD_COMMENT = 'comment';

    private const string FIELD_DATE_UPDATED = 'date_updated';

    private const string FIELD_DATE_CREATED = 'date_created';

    protected string $fileName = 'book_user_data.csv';

    protected ?string $tableName = BookUserData::TABLE_NAME;

    protected $signature = 'app:import:book_user_data';

    protected $description = 'Импортируем список BookUserData';

    protected int $batchSize = 200;

    protected bool $requireUpdateSequence = false;

    protected array $convertSchema = [
        self::FIELD_BOOK_ID => self::SCHEMA_FORCE_INTEGER,
        self::FIELD_USER_ID => self::SCHEMA_FORCE_INTEGER,
        self::FIELD_LISTEN_PRICE_VALUE => self::SCHEMA_INTEGER,
    ];

    protected function runBeforeImport(): void
    {
        BookUserData::truncate();
    }

    protected function createInsertingData(array $row): array
    {
        return [
            BookUserData::PARAM_BOOK_ID => $row[self::FIELD_BOOK_ID],
            BookUserData::PARAM_USER_ID => $row[self::FIELD_USER_ID],
            BookUserData::PARAM_RELEASE_DATE => $row[self::FIELD_RELEASE_DATE]
                ? $this->checkDateAndReturn(
                    $row[self::FIELD_BOOK_ID].'_'.$row[self::FIELD_USER_ID],
                    $row[self::FIELD_RELEASE_DATE]
                )
                : null,
            BookUserData::PARAM_LISTEN_PRICE_VALUE => $row[self::FIELD_LISTEN_PRICE_VALUE],
            BookUserData::PARAM_COMMENT => $row[self::FIELD_COMMENT],

            Model::UPDATED_AT => $row[self::FIELD_DATE_UPDATED],
            Model::CREATED_AT => $row[self::FIELD_DATE_CREATED],
        ];
    }
}
