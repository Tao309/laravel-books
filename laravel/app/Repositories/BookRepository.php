<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\BookUserDataDTO;
use App\DTOs\DtoInterface;
use App\Http\QueryFilters\BookQueryFilter;
use App\Models\Book;
use App\Models\BookUserData;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookRepository implements RepositoryInterface
{
    public function __construct(
        private readonly BookUserDataRepository $budRepository,
        private readonly BookQueryFilter $queryFilter,
    ) {}

    public function create(DtoInterface $dto): Book
    {
        DB::beginTransaction();

        $relationData = $dto->getRelationsData();

        try {
            $userId = Auth::id();

            $book = Book::newModelInstance($dto->getEntityData());

            $book->bindingType()->associate(Arr::get($relationData, Book::PARAM_BINDING_TYPE));

            if (Arr::has($relationData, Book::PARAM_PAPER_TYPE)) {
                $book->paperType()->associate(Arr::get($relationData, Book::PARAM_PAPER_TYPE));
            }

            if (Arr::has($relationData, Book::PARAM_PUBLISHING_HOUSE)) {
                $book->publishingHouse()->associate(Arr::get($relationData, Book::PARAM_PUBLISHING_HOUSE));
            }

            if (Arr::has($relationData, Book::PARAM_PUBLISHING_BRAND)) {
                $book->publishingBrand()->associate(Arr::get($relationData, Book::PARAM_PUBLISHING_BRAND));
            }

            if (Arr::has($relationData, Book::PARAM_BOOK_SERIES)) {
                $book->bookSeries()->associate(Arr::get($relationData, Book::PARAM_BOOK_SERIES));
            }

            $book->userCreated()->associate($userId);

            $book->save();

            $book->authors()->attach(Arr::get($relationData, Book::PARAM_AUTHORS));

            $this->budRepository->create(
                $book->id,
                BookUserDataDTO::fromArray([
                    BookUserData::PARAM_BOOK_ID => $book->id,
                    ...Arr::get($relationData, Book::PARAM_BOOK_USER_DATA, []),
                ])
            );

            DB::commit();

            return $this->query()->findOrFail($book->id);
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function update(int $id, DtoInterface $dto): Book
    {
        $relationData = $dto->getRelationsData();

        DB::beginTransaction();
        try {
            $book = Book::findOrFail($id);

            $book->bindingType()->associate(Arr::get($relationData, Book::PARAM_BINDING_TYPE));
            $book->paperType()->associate(Arr::get($relationData, Book::PARAM_PAPER_TYPE));
            $book->publishingHouse()->associate(Arr::get($relationData, Book::PARAM_PUBLISHING_HOUSE));
            $book->publishingBrand()->associate(Arr::get($relationData, Book::PARAM_PUBLISHING_BRAND));
            $book->bookSeries()->associate(Arr::get($relationData, Book::PARAM_BOOK_SERIES));

            $book->authors()->sync(Arr::get($relationData, Book::PARAM_AUTHORS));

            $book->update($dto->getEntityData());

            $budData = Arr::get($relationData, Book::PARAM_BOOK_USER_DATA, []);

            if (! empty($budData)) {
                $this->budRepository->update(
                    $book->id,
                    BookUserDataDTO::fromArray([
                        BookUserData::PARAM_BOOK_ID => $book->id,
                        ...$budData,
                    ])
                );
            }

            DB::commit();

            return $this->query()->findOrFail($book->id);
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function get(int $id): Book
    {
        return Book::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return $this->queryFilter->apply(
            Book::query(),
            $query->getDTO()
        )->get();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return $this->queryFilter->apply(
            Book::query(),
            $query->getDTO()
        )->paginate();
    }

    public function delete(int $id): bool
    {
        $entity = Book::query()->withoutEagerLoads()->findOrFail($id);

        $entity->authors()->detach();

        return $entity->delete();
    }

    /**
     * @return Builder<Book>
     */
    private function query(): Builder
    {
        return Book::query();
    }
}
