<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\Author;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorRepository implements RepositoryInterface
{
    public function create(array $data): Author
    {
        return Author::create($data);
    }

    public function get(int $id): Author
    {
        return Author::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return Author::all();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return Author::paginate();
    }

    public function update(int $id, array $data): Author
    {
        $entity = Author::findOrFail($id);
        $entity->update($data);

        return $entity;
    }

    public function delete($id): bool
    {
        $entity = Author::findOrFail($id);

        return $entity->delete();
    }
}
