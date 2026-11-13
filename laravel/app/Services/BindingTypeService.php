<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\BindingTypes\CreateBindingTypeCommand;
use App\CQRS\Commands\BindingTypes\DestroyBindingTypeCommand;
use App\CQRS\Commands\BindingTypes\UpdateBindingTypeCommand;
use App\CQRS\Queries\BindingTypes\GetBindingTypesQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\BindingType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class BindingTypeService implements ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetBindingTypesQuery($dto));
    }

    public function create(DtoInterface $dto): BindingType
    {
        $command = new CreateBindingTypeCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): BindingType
    {
        $command = new UpdateBindingTypeCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroyBindingTypeCommand($id));
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): ?LengthAwarePaginator
    {
        return null;
    }

    public function getById(int $id): ?Model
    {
        return null;
    }
}
