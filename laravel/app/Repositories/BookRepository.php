<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\BookUserDataDTO;
use App\DTOs\DtoInterface;
use App\Models\Book;
use App\Models\BookUserData;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookRepository implements RepositoryInterface
{
    public function __construct(
        private readonly BookUserDataRepository $bookUserDataRepository,
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

            $this->bookUserDataRepository->create(
                $book->id,
                BookUserDataDTO::fromArray([
                    BookUserData::PARAM_BOOK_ID => $book->id,
                    ...Arr::get($relationData, Book::PARAM_BOOK_USER_DATA, []),
                ])
            );

            DB::commit();

            return $book;
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

            $book->bindingType()->dissociate();
            $book->bindingType()->associate(Arr::get($relationData, Book::PARAM_BINDING_TYPE));

            $book->paperType()->dissociate();
            $book->paperType()->associate(Arr::get($relationData, Book::PARAM_PAPER_TYPE));

            $book->publishingHouse()->dissociate();
            $book->publishingHouse()->associate(Arr::get($relationData, Book::PARAM_PUBLISHING_HOUSE));

            $book->publishingBrand()->dissociate();
            $book->publishingBrand()->associate(Arr::get($relationData, Book::PARAM_PUBLISHING_BRAND));

            $book->bookSeries()->dissociate();
            $book->bookSeries()->associate(Arr::get($relationData, Book::PARAM_BOOK_SERIES));

            $book->authors()->detach();
            $book->authors()->attach(Arr::get($relationData, Book::PARAM_AUTHORS));

            $book->update($dto->getEntityData());

            $this->bookUserDataRepository->update(
                $book->id,
                BookUserDataDTO::fromArray([
                    BookUserData::PARAM_BOOK_ID => $book->id,
                    ...Arr::get($relationData, Book::PARAM_BOOK_USER_DATA, []),
                ])
            );

            DB::commit();

            return Book::findOrFail($book->id);
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
        return Book::all();
    }

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator
    {
        return Book::paginate();
    }

    public function delete($id): bool
    {
        $entity = Book::findOrFail($id);

        $this->bookUserDataRepository->delete($id);

        return $entity->delete();
    }
}
