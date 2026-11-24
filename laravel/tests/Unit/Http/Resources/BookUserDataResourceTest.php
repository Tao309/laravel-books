<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\BookUserDatas\BookUserDataResource;
use App\Models\Book;
use App\Models\BookUserData;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class BookUserDataResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_resolves(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $bud = BookUserData::factory()->create([
            BookUserData::PARAM_BOOK_ID => $book->id,
            BookUserData::PARAM_USER_ID => $user->id,
        ]);

        $data = (new BookUserDataResource($bud))->toArray(new Request);
        $this->assertIsArray($data);
    }
}
