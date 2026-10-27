<?php

namespace App\Repositories;

use App\Models\Author;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorRepository implements RepositoryInterface
{
    public function create(array $data): Author
    {
        return Author::create($data);
    }

    public function get($id): Author
    {
        return Author::findOrFail($id);
    }

    public function getAllPaginate($query): LengthAwarePaginator
    {
        return Author::paginate();
    }

    public function update(int $id, array $data): Author
    {
        $foundAuthor = Author::findOrFail($id);

        $foundAuthor->update($data);

        return $foundAuthor;
    }

    public function delete($id): bool
    {
        $foundAuthor = Author::findOrFail($id);

        return $foundAuthor->delete();
    }
}
