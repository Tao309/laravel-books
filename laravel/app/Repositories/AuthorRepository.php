<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use App\Http\QueryFilters\AuthorQueryFilter;
use App\Models\Author;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorRepository implements RepositoryInterface
{
    public function __construct(
        private readonly AuthorQueryFilter $queryFilter,
    ) {}

    public function create(DtoInterface $dto): Author
    {
        return Author::create($dto->getEntityData());
    }

    public function get(int $id): Author
    {
        return Author::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return $this->queryFilter->apply(
            Author::query(),
            $query->getDTO()
        )->get();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return $this->queryFilter->apply(
            Author::query(),
            $query->getDTO()
        )->paginate();
    }

    public function update(int $id, DtoInterface $dto): Author
    {
        $entity = Author::findOrFail($id);
        $entity->update($dto->getEntityData());

        return $entity;
    }

    public function delete(int $id): bool
    {
        $entity = Author::findOrFail($id);

        return $entity->delete();
    }
}
