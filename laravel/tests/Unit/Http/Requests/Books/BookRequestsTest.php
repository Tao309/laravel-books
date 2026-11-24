<?php

namespace Tests\Unit\Http\Requests\Books;

use App\Http\Requests\Books\StoreBookRequest;
use App\Http\Requests\Books\UpdateBookRequest;
use PHPUnit\Framework\TestCase;

class BookRequestsTest extends TestCase
{
    public function test_store_request_has_rules(): void
    {
        $request = new StoreBookRequest;
        $rules = $request->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('publish_year', $rules);
    }

    public function test_update_request_has_rules(): void
    {
        $request = new UpdateBookRequest;
        $rules = $request->rules();

        $this->assertIsArray($rules);
        $this->assertArrayHasKey('title', $rules);
    }
}
