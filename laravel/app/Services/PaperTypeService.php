<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\PaperTypes\CreatePaperTypeCommand;
use App\CQRS\Commands\PaperTypes\DestroyPaperTypeCommand;
use App\CQRS\Commands\PaperTypes\UpdatePaperTypeCommand;
use App\CQRS\Queries\PaperTypes\GetPaperTypeByIdQuery;
use App\CQRS\Queries\PaperTypes\GetPaperTypesQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\PaperType;
use App\Services\Interfaces\GetListPaginateServiceInterface;
use App\Services\Interfaces\GetListServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class PaperTypeService implements GetListPaginateServiceInterface, GetListServiceInterface, ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetPaperTypesQuery($dto));
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): LengthAwarePaginator
    {
        return $this->bus->handle(new GetPaperTypesQuery($dto));
    }

    public function getById(int $id): PaperType
    {
        return $this->bus->handle(new GetPaperTypeByIdQuery($id));
    }

    public function create(DtoInterface $dto): PaperType
    {
        $command = new CreatePaperTypeCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): PaperType
    {
        $command = new UpdatePaperTypeCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroyPaperTypeCommand($id));
    }
}
