<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\Authors\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\Framework\TestCase без Laravel).
 */
class AuthorResourceTest extends TestCase
{
    public function test_resolves_id_names_and_timestamps(): void
    {
        $data = AuthorResource::make($this->author())->resolve(new Request);

        $this->assertSame(5, $data['id']);
        $this->assertSame('Лев', $data['first_name']);
        $this->assertSame('Толстой', $data['last_name']);
        $this->assertSame('2026-11-10 12:00:00', $data['updated_at']);
        $this->assertSame('2026-11-11 13:30:00', $data['created_at']);
    }

    public function test_last_name_is_null_when_not_set(): void
    {
        $author = new Author;
        $author->id = 5;
        $author->first_name = 'Лев';

        $data = AuthorResource::make($author)->resolve(new Request);

        $this->assertNull($data['last_name']);
    }

    public function test_timestamps_are_null_for_unpersisted_model(): void
    {
        $author = new Author;
        $author->id = 5;
        $author->first_name = 'Лев';

        $data = AuthorResource::make($author)->resolve(new Request);

        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    private function author(): Author
    {
        $author = new Author;
        $author->id = 5;
        $author->first_name = 'Лев';
        $author->last_name = 'Толстой';
        $author->created_at = Carbon::create(2026, 11, 11, 13, 30, 0);
        $author->updated_at = Carbon::create(2026, 11, 10, 12, 0, 0);

        return $author;
    }
}
