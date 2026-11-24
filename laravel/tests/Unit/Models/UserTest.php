<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_table_name(): void
    {
        $model = new User;
        $this->assertSame('users', User::TABLE_NAME);
        $this->assertSame('users', $model->getTable());
    }
}
