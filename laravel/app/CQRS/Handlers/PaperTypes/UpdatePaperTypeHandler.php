<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Commands\PaperTypes\UpdatePaperTypeCommand;
use App\Exceptions\ApiException;
use App\Models\PaperType;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PaperTypeRepository;

class UpdatePaperTypeHandler extends AbstractPaperTypeHandler
{
    /**
     * @param  PaperTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    /**
     * @throws ApiException
     */
    public function handle(UpdatePaperTypeCommand $command): PaperType
    {
        return $this->repository->update($command->id, $command->dto->toArray());
    }
}
