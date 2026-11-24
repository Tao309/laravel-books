<?php

namespace Tests\Unit\Rules;

use App\Rules\BookRatingRule;
use PHPUnit\Framework\TestCase;

class BookRatingRuleTest extends TestCase
{
    public function test_accepts_int_and_float(): void
    {
        $rule = new BookRatingRule;
        $failed = false;
        $fail = function () use (&$failed): void {
            $failed = true;
        };

        $rule->validate('rating', 4, $fail);
        $rule->validate('rating', 4.5, $fail);

        $this->assertFalse($failed);
    }

    public function test_rejects_string(): void
    {
        $rule = new BookRatingRule;
        $message = null;
        $rule->validate('rating', '4.5', function (string $msg) use (&$message) {
            $message = $msg;
        });

        $this->assertSame('The :attribute must be float or integer.', $message);
    }
}
