<?php

namespace Tests\Unit\CQRS;

use PHPUnit\Framework\TestCase;

class SourceProductsBusMappingTest extends TestCase
{
    public function test_commands_and_queries_have_handlers(): void
    {
        $app = dirname(__DIR__, 3) . '/app/CQRS';
        $domains = ['SourceProducts'];
        foreach ($domains as $domain) {
            foreach (['Commands', 'Queries'] as $kind) {
                $dir = $app . '/' . $kind . '/' . $domain;
                if (! is_dir($dir)) {
                    continue;
                }
                foreach (glob($dir . '/*.php') as $file) {
                    $class = 'App\\CQRS\\' . $kind . '\\' . $domain . '\\' . basename($file, '.php');
                    $this->assertTrue(class_exists($class), "Missing class {$class}");
                    $handler = preg_replace('/(Command|Query)$/', 'Handler', basename($file, '.php'));
                    $handlerClass = 'App\\CQRS\\Handlers\\' . $domain . '\\' . $handler;
                    $this->assertTrue(class_exists($handlerClass), "Missing handler {$handlerClass} for {$class}");
                }
            }
        }
    }
}
