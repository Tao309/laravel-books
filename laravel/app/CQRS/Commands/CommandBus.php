<?php

namespace App\CQRS\Commands;

use Illuminate\Contracts\Container\Container;

final readonly class CommandBus
{
    public function __construct(private Container $container) {}

    public function dispatch(object $command): mixed
    {
        $handlerClass = $this->resolveHandler($command);
        $handler = $this->container->make($handlerClass);

        return $handler->handle($command);
    }

    private function resolveHandler(object $command): string
    {
        $className = get_class($command);
        $classNames = explode('\\', $className);
        $baseClassName = class_basename($command);

        if (count($classNames) < 4) {
            throw new \RuntimeException("Command {$baseClassName} is not correct");
        }

        if ($classNames[2] === 'Queries') {
            $handler = str_replace('Query', 'Handler', $baseClassName);
        } else {
            $handler = str_replace('Command', 'Handler', $baseClassName);
        }

        $class = 'App\\CQRS\\Handlers\\'.$classNames[3].'\\'.$handler;

        if (! class_exists($class)) {
            throw new \RuntimeException("No handler found for {$baseClassName}");
        }

        return $class;
    }
}
