<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\Shops\CreateShopCommand;
use App\CQRS\Commands\Shops\DestroyShopCommand;
use App\CQRS\Commands\Shops\UpdateShopCommand;
use App\CQRS\Queries\Shops\GetShopByIdQuery;
use App\CQRS\Queries\Shops\GetShopsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\Shop;
use App\Services\Interfaces\GetListServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class ShopService implements GetListServiceInterface, ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getById(int $id): Shop
    {
        return $this->bus->handle(new GetShopByIdQuery($id));
    }

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetShopsQuery($dto));
    }

    public function create(DtoInterface $dto): ?Model
    {
        $command = new CreateShopCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): ?Model
    {
        $command = new UpdateShopCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroyShopCommand($id));
    }
}
