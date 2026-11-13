<?php

namespace App\CQRS;

use App\Exceptions\HandlerNotFoundException;
use Illuminate\Contracts\Container\Container;

final readonly class Bus
{
    public function __construct(private Container $container) {}

    public function handle(object $object): mixed
    {
        return $this->container->make($this->resolveHandler($object))->handle($object);
    }

    private function resolveHandler(object $object): string
    {
        $className = $object::class;

        $parts = explode('\\', $className);

        $base = array_pop($parts); // CreateModelCommand
        $domain = array_pop($parts); // Models
        $kind = array_pop($parts); // Commands | Queries

        if ($parts !== ['App', 'CQRS'] || ! \in_array($kind, ['Commands', 'Queries'], true)) {
            throw new HandlerNotFoundException(
                "Message {$className} must be in App\\CQRS\\{Commands,Queries}\\{Domain} namespace"
            );
        }

        $handlerBase = preg_replace('/(Command|Query)$/', 'Handler', $base, 1);

        if ($handlerBase === null || $handlerBase === $base) {
            throw new HandlerNotFoundException("Message {$className} must end with Command or Query");
        }

        $handler = \sprintf('App\\CQRS\\Handlers\\%s\\%s', $domain, $handlerBase);

        if (! class_exists($handler) || ! method_exists($handler, 'handle')) {
            throw new HandlerNotFoundException("Handler {$handler} for {$className} was not found");
        }

        return $handler;
    }
}
