<?php

namespace Tests\Unit\Rules;

use App\Rules\BookRatingRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BookRatingRuleTest extends TestCase
{
    #[DataProvider('validValuesProvider')]
    public function test_valid_values_pass_validation(mixed $value): void
    {
        $rule = new BookRatingRule;

        $failed = false;

        $rule->validate(
            'rating',
            $value,
            function () use (&$failed): void {
                $failed = true;
            }
        );

        $this->assertFalse($failed);
    }

    #[DataProvider('invalidValuesProvider')]
    public function test_invalid_values_fail_validation(mixed $value): void
    {
        $rule = new BookRatingRule;

        $errorMessage = null;

        $rule->validate(
            'rating',
            $value,
            function ($message) use (&$errorMessage): void {
                $errorMessage = $message;
            }
        );

        $this->assertSame(
            'The :attribute must be float or integer.',
            $errorMessage
        );
    }

    public static function validValuesProvider(): array
    {
        return [
            'positive integer' => [5],
            'zero integer' => [0],
            'negative integer' => [-5],
            'positive float' => [4.5],
            'zero float' => [0.0],
            'negative float' => [-4.5],
        ];
    }

    public static function invalidValuesProvider(): array
    {
        return [
            'numeric string' => ['4.5'],
            'integer string' => ['5'],
            'null' => [null],
            'empty string' => [''],
            'boolean true' => [true],
            'boolean false' => [false],
            'array' => [[]],
            'object' => [new \stdClass],
        ];
    }
}
