<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class RunAllImportsCommand extends Command
{
    protected $signature = 'app:import:run-all';

    protected $description = 'Запуск всех команд по импорту';

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
        $output = $this->output;

        $io = new SymfonyStyle($input, $output);
        $showParsingLog = (bool) ($input->getOption('show-parsing-log') ?? 1);
        $isFake = (bool) ($input->getOption('fake') ?? 1);
        $justCheckParsing = (bool) ($input->getOption('just-check-parsing') ?? 1);

        $commandNames = [
            'app:import:books_series',
            'app:import:publishing_brands',
            'app:import:publishing_houses',
        ];

        try {
            foreach ($commandNames as $commandName) {
                $command = $this->getApplication()->find($commandName);

                if (! $command) {
                    throw new \RuntimeException(\sprintf('Команда "%s" не найдена', $commandName));
                }

                $io->info(\sprintf('Запуск команды %s', $commandName));

                $arguments = [
                    'command' => $commandName,
                    '--show-parsing-log' => $showParsingLog,
                    '--fake' => $isFake,
                    '--just-check-parsing' => $justCheckParsing,
                ];

                $subCommandInput = new ArrayInput($arguments);

                $returnCode = $command->run($subCommandInput, $output);

                if ($returnCode === Command::SUCCESS) {
                    $io->success(\sprintf('Команда "%s" успешна завершена', $commandName));

                    continue;
                }

                throw new \RuntimeException(\sprintf('Команда "%s" завершена с ошибкой', $commandName));
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
