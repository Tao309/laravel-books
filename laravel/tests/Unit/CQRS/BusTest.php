<?php

namespace Tests\Unit\CQRS;

use App\CQRS\Bus;
use App\CQRS\Commands\Books\CreateBookCommand;
use App\CQRS\Commands\Products\DestroyProductCommand;
use App\CQRS\Commands\Test\NoHandleCommand;
use App\CQRS\Commands\Test\NotAMessage;
use App\CQRS\Commands\Test\OrphanCommand;
use App\CQRS\Commands\Test\Sub\TooDeepCommand;
use App\CQRS\Handlers\Books\CreateBookHandler;
use App\CQRS\Handlers\Cities\GetCitiesHandler;
use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\CQRS\Services\Test\WrongKindCommand;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Exceptions\HandlerNotFoundException;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\TestCase;

class BusTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        // Фикстурам нужны имена в App\CQRS\*, но PSR-4 требует, чтобы классы
        // App\* физически жили в app/. Объявляем их в рантайме: имена
        // существуют в процессе теста, файлов в App\* неймспейсах нет.
        if (class_exists(OrphanCommand::class, false)) {
            return;
        }

        eval(<<<'PHP'
        namespace App\CQRS\Commands\Test {
            final class OrphanCommand {
                public function __construct(public int $value) {}
            }
            final class NoHandleCommand {
                public function __construct(public int $value) {}
            }
            final class NotAMessage {}
        }
        namespace App\CQRS\Commands\Test\Sub {
            final class TooDeepCommand {}
        }
        namespace App\CQRS\Services\Test {
            final class WrongKindCommand {}
        }
        namespace App\CQRS\Handlers\Test {
            final class NoHandleHandler {}
        }
        PHP);
    }

    // ---------- резолвинг: строка в контейнер + проброс объекта ----------

    public function test_command_resolves_to_handler_in_same_domain(): void
    {
        $command = new CreateBookCommand($this->createStub(DtoInterface::class));
        $stubHandler = new class
        {
            public ?object $received = null;

            public function handle(object $message): int
            {
                $this->received = $message;

                return 42;
            }
        };

        $container = $this->createMock(Container::class);
        $container->expects($this->once())
            ->method('make')
            ->with(CreateBookHandler::class) // Books + Command → Handler
            ->willReturn($stubHandler);

        $result = (new Bus($container))->handle($command);

        $this->assertSame(42, $result);                    // результат как есть
        $this->assertSame($command, $stubHandler->received); // тот же объект
    }

    public function test_query_resolves_to_handler(): void
    {
        $query = new GetCitiesQuery($this->createStub(CollectionQueryDtoInterface::class));

        $container = $this->createMock(Container::class);
        $container->expects($this->once())
            ->method('make')
            ->with(GetCitiesHandler::class)
            ->willReturn(new class
            {
                public function handle(object $message): string
                {
                    return 'query-handled';
                }
            });

        $this->assertSame('query-handled', (new Bus($container))->handle($query));
    }

    public function test_handler_receives_message_unchanged(): void
    {
        $command = new DestroyProductCommand(5);

        $stubHandler = new class
        {
            public ?object $received = null;

            public function handle(object $message): object
            {
                $this->received = $message;

                return $message;
            }
        };

        $container = $this->createMock(Container::class);
        $container->method('make')->willReturn($stubHandler);

        $this->assertSame($command, (new Bus($container))->handle($command));
    }

    // ---------- guard 1: неймспейс ----------

    public function test_rejects_object_outside_cqrs_namespace(): void
    {
        $this->assertRejected(
            new \stdClass,
            'must be in App\CQRS\{Commands,Queries}\{Domain} namespace',
        );
    }

    public function test_rejects_wrong_kind_namespace(): void
    {
        $this->assertRejected(
            new WrongKindCommand,
            'must be in App\CQRS\{Commands,Queries}\{Domain} namespace',
        );
    }

    public function test_rejects_message_nested_too_deep(): void
    {
        $this->assertRejected(
            new TooDeepCommand,
            'must be in App\CQRS\{Commands,Queries}\{Domain} namespace',
        );
    }

    // ---------- guard 2: суффикс ----------

    public function test_rejects_class_not_ending_with_command_or_query(): void
    {
        $this->assertRejected(
            new NotAMessage,
            'must end with Command or Query',
        );
    }

    // ---------- guard 3: хендлер ----------

    public function test_rejects_when_handler_class_missing(): void
    {
        $this->assertRejected(
            new OrphanCommand(5),
            'Handler App\CQRS\Handlers\Test\OrphanHandler for App\CQRS\Commands\Test\OrphanCommand was not found',
        );
    }

    public function test_rejects_when_handler_has_no_handle_method(): void
    {
        $this->assertRejected(
            new NoHandleCommand(5),
            'Handler App\CQRS\Handlers\Test\NoHandleHandler for App\CQRS\Commands\Test\NoHandleCommand was not found',
        );
    }

    private function assertRejected(object $message, string $expectedMessage): void
    {
        $container = $this->createMock(Container::class);
        $container->expects($this->never())->method('make'); // до make() дело не доходит

        $bus = new Bus($container);

        $this->expectException(HandlerNotFoundException::class);
        $this->expectExceptionMessage($expectedMessage);

        $bus->handle($message);
    }
}
