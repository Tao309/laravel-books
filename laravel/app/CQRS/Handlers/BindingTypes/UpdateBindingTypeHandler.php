<?php

namespace App\CQRS\Handlers\BindingTypes;

use App\CQRS\Commands\BindingTypes\UpdateBindingTypeCommand;
use App\Exceptions\ApiException;
use App\Models\BindingType;
use App\Repositories\BindingTypeRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class UpdateBindingTypeHandler
{
    /**
     * @param  BindingTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    /**
     * @throws ApiException
     */
    public function handle(UpdateBindingTypeCommand $command): BindingType
    {
        return $this->repository->update($command->id, [
            BindingType::LABEL => $command->label,
        ]);
    }
}
