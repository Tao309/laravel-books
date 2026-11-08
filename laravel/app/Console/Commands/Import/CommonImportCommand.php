<?php

declare(strict_types=1);

namespace App\Console\Commands\Import;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class CommonImportCommand extends Command
{
    protected ?SymfonyStyle $io = null;

    protected int $added = 0;

    protected bool $showParsingLog = true;

    protected bool $isFake = true;

    protected bool $justCheckParsing = false;

    protected array $importData = [];

    protected string $fileName = '';

    // Таблица для импорта
    protected ?string $tableName = null;

    // Идентификатор для таблицы
    protected string $idParam = 'id';

    // Массив предупреждений, которые не влияют на парсинг
    protected array $warnings = [];

    // Массив для вставки в таблицу
    protected array $insertBulkData = [];

    // Размер батча при обработке записи в БД
    protected int $batchSize = 500;

    // Обновлять sequence
    protected bool $requireUpdateSequence = true;

    // Во что конвертировать поля
    protected array $convertSchema = [
        // 'field_name' => self::SCHEMA_FORCE_INTEGER, => convertToInteger с force
        // 'field_name' => self::SCHEMA_INTEGER, => convertToInteger без force
        // 'field_name' => self::SCHEMA_BOOL, => convertToBool
    ];

    protected const string SCHEMA_FORCE_INTEGER = 'force_integer';

    protected const string SCHEMA_INTEGER = 'integer';

    protected const string SCHEMA_BOOL = 'boolean';

    abstract protected function createInsertingData(array $row): array;

    protected function runBeforeImport(): void {}

    protected function runAfterImport(): void {}

    protected function configure(): void
    {
        $this
            ->addOption('show-parsing-log', null, InputArgument::OPTIONAL, 'Показывать детали парсинга?', 1)
            ->addOption('fake', null, InputArgument::OPTIONAL, 'Фейковый запрос без записи?', 1)
            ->addOption('just-check-parsing', null, InputArgument::OPTIONAL, 'Проверить только парсинг?', 1);
    }

    public function handle(): int
    {
        $input = $this->input;

        $this->io = new SymfonyStyle($input, $this->output);
        $this->added = 0;
        $this->showParsingLog = (bool) ($input->getOption('show-parsing-log') ?? 1);
        $this->isFake = (bool) ($input->getOption('fake') ?? 1);
        $this->justCheckParsing = (bool) ($input->getOption('just-check-parsing') ?? 1);

        $start = microtime(true);

        try {
            $this->checkTable();
            $this->runBeforeImport();

            $this->checkTableIsEmpty();
            $this->assembleImportData();

            if (! $this->justCheckParsing) {
                $this->persistImportData();
            }

            $this->updateSequence();
        } catch (\Throwable $e) {
            $this->io->error($e->getMessage());

            return Command::FAILURE;
        }

        $this->io->title('Завершение импорта: '.static::COMMAND_LABEL);
        $this->io->section('Добавлено: '.$this->added);

        if ($this->isFake) {
            $this->io->warning('Фейковый запуск команды');
        }

        $this->runAfterImport();

        $this->showWarnings();

        $elapsed = microtime(true) - $start;

        $minutes = floor(($elapsed % 3600) / 60);
        $seconds = floor($elapsed % 60);
        $milliseconds = round(($elapsed - floor($elapsed)) * 1000);

        $this->io->section(sprintf(
            'Время выполнения: %02d мин. %02d сек. %03d мс.',
            $minutes,
            $seconds,
            $milliseconds,
        ));

        return Command::SUCCESS;
    }

    protected function addWarning(string $message): void
    {
        $this->warnings[] = $message;
    }

    protected function showWarnings(): void
    {
        if (empty($this->warnings)) {
            return;
        }

        $this->io->warning('// > Warnings: ('.count($this->warnings).')');

        $i = 1;
        foreach ($this->warnings as $error) {
            $this->io->text($i++.'. '.$error);
        }

        $this->io->warning('// < Warnings: ('.count($this->warnings).')');

    }

    protected function checkTable(): void
    {
        if (empty($this->tableName)) {
            throw new \RuntimeException('Название таблицы (tableName) должно быть указано');
        }

        if (! Schema::hasTable($this->tableName)) {
            throw new \RuntimeException('Таблица "'.$this->tableName.'" не найдена');
        }
    }

    protected function checkTableIsEmpty(): void
    {
        $exists = (bool) DB::table($this->tableName)->limit(1)->count();

        if ($exists) {
            throw new \RuntimeException(\sprintf('Таблица %s должна быть пуста, очистите таблицу.', $this->tableName));
        }
    }

    protected function checkDateAndReturn(int|string $id, ?string $date = null): Carbon
    {
        if (empty($date) || $date === '0000-00-00 00:00:00') {
            $this->addWarning('Дата не корректна id = '.$id.': ('.$date.')');

            return Carbon::now();
        }

        try {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date);
        } catch (\Throwable $e) {
            $this->addWarning('Дата не корректна id = '.$id.': '.$date.': '.$e->getMessage());

            return Carbon::now();
        }
    }

    protected function fillImportRow(array $data): void
    {
        $this->importData[] = $data;
    }

    protected function assembleImportData(): void
    {
        $io = $this->io;

        $io->title('Начало импорта: '.static::COMMAND_LABEL);
        if ($this->isFake) {
            $io->warning('Фейковый запуск команды');
        }

        $io->section('Поиск данных для импорта из файла');
        $filePath = base_path().'/app/Console/Commands/Import/csv/'.$this->fileName;

        if (empty($this->fileName) || ! file_exists($filePath) || ! is_readable($filePath)) {
            throw new \RuntimeException(\sprintf('Файл "%s" не найден или недоступен к чтению.', $filePath));
        }

        if (($handle = fopen($filePath, 'r')) === false) {
            throw new \RuntimeException('Ошибка при открытии файла.');
        }

        $io->title('Парсинг CSV файла...');

        $headers = fgetcsv($handle, 0, ',');
        if ($this->showParsingLog) {
            $io->writeln(
                \sprintf('Заголовки: %s', implode(' | ', $headers))
            );

            $io->newLine();

            $progressBar = new ProgressBar($this->output);
            $progressBar->setFormat('Обработано строк: %current%');
            $progressBar->start();
        }

        $rowCount = 0;
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $rowCount++;
            $rowData = array_combine($headers, $row);

            foreach ($rowData as $index => $data) {
                $rowData[$index] = strtolower(trim($data)) === 'null' ? null : trim($data);

                if (! empty($this->convertSchema[$index])) {
                    $rowData[$index] = $this->convertField($this->convertSchema[$index], $rowData[$index]);
                }
            }

            $this->fillImportRow($rowData);

            if ($this->showParsingLog) {
                $progressBar->setProgress($rowCount);
            }
        }

        fclose($handle);

        if ($this->showParsingLog) {
            $progressBar->finish();
            $io->newLine(2);
        }

        if (! $rowCount) {
            throw new \RuntimeException('Не найдены строки в файле для парсинга.');
        }
    }

    protected function persistImportData(): void
    {
        $this->io->title(static::COMMAND_LABEL.' => Запись в БД...');

        $totalRecords = \count($this->importData);

        $this->io->progressStart($totalRecords);

        if (! $this->isFake) {
            DB::beginTransaction();
        }

        try {
            for ($i = 0; $i < $totalRecords; $i++) {
                try {
                    $importData = $this->importData[$i];

                    $this->insertBulkData[] = $this->createInsertingData($importData);

                    if (($i % $this->batchSize) === 0) {
                        if (! $this->isFake) {
                            $this->applyInsertBulkData();
                        }
                    }

                    $this->added++;
                    $this->io->progressAdvance();
                } catch (\Throwable $e) {
                    $this->io->error(json_encode($importData, \JSON_PRETTY_PRINT));

                    throw $e;
                }
            }

            if (! $this->isFake) {
                $this->applyInsertBulkData();

                DB::commit();
            }
        } catch (\Throwable $e) {
            if (! $this->isFake) {
                DB::rollBack();
            }

            Log::error($e);

            throw new \RuntimeException('Ошибка при записи "'.static::COMMAND_LABEL.'" в БД: '.$e->getMessage());
        }

        $this->io->progressFinish();
    }

    private function applyInsertBulkData(): void
    {
        DB::table($this->tableName)->insert($this->insertBulkData);

        $this->insertBulkData = [];
    }

    protected function updateSequence(): void
    {
        if (! $this->tableName || ! $this->requireUpdateSequence) {
            return;
        }

        $pdoDriverName = DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);

        $tableName = DB::getTablePrefix().$this->tableName;

        if ($pdoDriverName === 'pgsql') {
            DB::update("SELECT setval(pg_get_serial_sequence('".$tableName."', 'id'), COALESCE(MAX(id), 0) + 1, false) FROM ".$tableName.';');

            return;
        }

        $lastId = $this->getLastId($tableName, $this->idParam);

        DB::update('ALTER TABLE '.$tableName.' AUTO_INCREMENT = '.($lastId + 1).';');
    }

    protected function getLastId(string $tableName, $paramId = 'id'): int
    {
        return (int) DB::table($tableName)->max($paramId);
    }

    private function convertField($convertType, $field = null)
    {
        return match ($convertType) {
            self::SCHEMA_FORCE_INTEGER => $this->convertToInteger($field, true),
            self::SCHEMA_INTEGER => $this->convertToInteger($field),
            self::SCHEMA_BOOL => $this->convertToBool($field),
            default => $field,
        };
    }

    protected function convertToInteger(?string $value, $force = false): ?int
    {
        return $force ? (int) $value : ($value ? (int) $value : null);
    }

    protected function convertToBool(?string $value): bool
    {
        return (bool) $value;
    }
}
