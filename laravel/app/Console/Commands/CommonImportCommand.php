<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Exceptions\SkipRowImportException;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    // Обновить sequence по таблице, если она указана
    protected ?string $seqTable = null;

    // Идентификатор для таблицы
    protected string $idParam = 'id';

    abstract protected function fillImportRow(array $row): void;

    /**
     * @throws SkipRowImportException
     */
    abstract protected function createEntityByImportRowData(array $row): mixed;

    protected function runBeforeImport(): void {}

    protected function runBeforeFlush(): void {}

    protected function runAtEnd(): void {}

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

        try {
            $this->checkTableIsEmpty();
            $this->assembleImportData();
            $this->runBeforeImport();

            if (! $this->justCheckParsing) {
                $this->persistImportData();
            }

            $this->updateSequence();
            $this->runAtEnd();
        } catch (\Throwable $e) {
            $this->io->error($e->getMessage());

            return Command::FAILURE;
        }

        $this->io->title('Завершение импорта: '.static::COMMAND_LABEL);
        $this->io->section('Добавлено: '.$this->added);

        if ($this->isFake) {
            $this->io->warning('Фейковый запуск команды');
        }

        return Command::SUCCESS;
    }

    protected function checkTableIsEmpty(): void
    {
        $exists = (bool) DB::table($this->seqTable)->limit(1)->count();

        if ($exists) {
            throw new \RuntimeException(\sprintf('Таблица %s должна быть пуста, очистите таблицу.', $this->seqTable));
        }
    }

    protected function checkDateAndReturn(?string $date = null): Carbon
    {
        $io = $this->io;

        if (empty($date) || $date === '0000-00-00 00:00:00') {
            $io->warning('Дата не корректна: '.$date);

            return Carbon::now();
        }

        try {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date);
        } catch (\Throwable $e) {
            $io->warning('Дата не корректна: '.$date.': '.$e->getMessage());

            return Carbon::now();
        }
    }

    protected function assembleImportData(): void
    {
        $io = $this->io;

        $io->title('Начало импорта: '.static::COMMAND_LABEL);
        if ($this->isFake) {
            $io->warning('Фейковый запуск команды');
        }

        $io->section('Поиск данных для импорта из файла');
        $filePath = base_path().'/database/migrations/import/'.$this->fileName;

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
                \sprintf('Заголовок: %s', implode(' | ', $headers))
            );
        }

        $rowCount = 0;
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $rowCount++;

            if ($this->showParsingLog) {
                $io->writeln(
                    \sprintf('Строка %d: %s', $rowCount, implode(' | ', $row))
                );
            }

            foreach ($row as $index => $rowData) {
                $row[$index] = strtolower(trim($rowData)) === 'null' ? null : trim($rowData);
            }

            $this->fillImportRow($row);
        }

        fclose($handle);

        if (! $rowCount) {
            throw new \RuntimeException('Не найдены строки в файле для парсинга.');
        }
    }

    protected function persistImportData(): void
    {
        $this->io->title(static::COMMAND_LABEL.' => Запись в БД...');

        $batchSize = 500;
        $totalRecords = \count($this->importData);

        $this->io->progressStart($totalRecords);

        if (! $this->isFake) {
            DB::beginTransaction();
        }

        try {
            for ($i = 0; $i < $totalRecords; $i++) {
                try {
                    $importData = $this->importData[$i];
                    $entity = $this->createEntityByImportRowData($importData);

                    if (! $entity) {
                        continue;
                    }

                    if (($i % $batchSize) === 0) {
                        if (! $this->isFake) {
                            $this->runBeforeFlush();
                        }
                    }

                    $this->added++;
                    $this->io->progressAdvance();
                } catch (SkipRowImportException) {
                    continue;
                } catch (\Throwable $e) {
                    $this->io->error(json_encode($importData, \JSON_PRETTY_PRINT));

                    throw $e;
                }
            }

            if (! $this->isFake) {
                $this->runBeforeFlush();
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

    protected function updateSequence(): void
    {
        if (! $this->seqTable) {
            return;
        }

        $pdoDriverName = DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);

        $tableName = DB::getTablePrefix().$this->seqTable;

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
}
