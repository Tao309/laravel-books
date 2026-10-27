<?php

namespace App\Services;

use App\Commands\BindingTypes\CreateBindingTypeCommand;
use App\Commands\BindingTypes\DestroyBindingTypeCommand;
use App\Commands\BindingTypes\UpdateBindingTypeCommand;
use App\Handlers\BindingTypes\CreateBindingTypeHandler;
use App\Handlers\BindingTypes\DestroyBindingTypeHandler;
use App\Handlers\BindingTypes\GetBindingTypesHandler;
use App\Handlers\BindingTypes\UpdateBindingTypeHandler;
use App\Models\BindingType;
use App\Queries\BindingTypes\GetBindingTypesQuery;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

readonly class BindingTypeService
{
    public function __construct(
        private DestroyBindingTypeHandler $destroyBindingTypeHandler,
        private UpdateBindingTypeHandler $updateBindingTypeHandler,
        private CreateBindingTypeHandler $createBindingTypeHandler,
        private GetBindingTypesHandler $getBindingTypesHandler,
    ) {}

    public function getList(): Collection
    {
        return $this->getBindingTypesHandler->handle(new GetBindingTypesQuery);
    }

    public function create(array $validatedData): JsonResource
    {
        $command = new CreateBindingTypeCommand(
            $validatedData[BindingType::LABEL],
        );

        return $this->createBindingTypeHandler->handle($command)->toResource();
    }

    public function updateById(int $id, array $validatedData): JsonResource
    {
        $command = new UpdateBindingTypeCommand(
            $id,
            $validatedData[BindingType::LABEL]
        );

        return $this->updateBindingTypeHandler->handle($command)->toResource();
    }

    public function deleteById(int $id): bool
    {
        return $this->destroyBindingTypeHandler->handle(new DestroyBindingTypeCommand($id));
    }
}
