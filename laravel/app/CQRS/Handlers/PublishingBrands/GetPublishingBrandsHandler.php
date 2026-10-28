<?php

namespace App\CQRS\Handlers\PublishingBrands;

use App\CQRS\Queries\PublishingBrands\GetPublishingBrandsQuery;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetPublishingBrandsHandler extends AbstractPublishingBrandHandler
{
    public function __construct(RepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function handle(GetPublishingBrandsQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
