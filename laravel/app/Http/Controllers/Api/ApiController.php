<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequestInterface;
use App\Http\Requests\RequestInterface;
use App\Http\Resources\PaginateResource;
use App\Http\Response\ResponseContext;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

abstract class ApiController extends Controller
{
    public function __construct(
        protected readonly ResponseContext $context,
        protected ?ServiceInterface $service,
        protected ?string $resourceClass
    ) {}

    protected function indexAction(IndexRequestInterface $request): JsonResponse
    {
        return $this->toJson(
            $this->getService()->getList($request->toDTO())->toResourceCollection()
        );
    }

    protected function indexActionPaginate(IndexRequestInterface $request): JsonResponse
    {
        if ($request->toDTO()->hasFilters()) {
            return $this->indexAction($request);
        }

        return $this->toJson(
            PaginateResource::make($this->getService()->getListPaginate($request->toDTO()), $this->getResourceClass())
        );
    }

    protected function storeAction(RequestInterface $request): JsonResponse
    {
        return $this->toJson(
            $this->getService()->create($request->toDTO())->toResource(),
            ResponseAlias::HTTP_CREATED
        );
    }

    protected function showAction(int $id): JsonResponse
    {
        return $this->toJson($this->getService()->getById($id)->toResource());
    }

    protected function updateAction(RequestInterface $request, int $id): JsonResponse
    {
        return $this->toJson(
            $this->getService()->updateById($id, $request->toDTO())->toResource()
        );
    }

    protected function destroyAction(int $id): JsonResponse
    {
        return $this->toJson($this->getService()->deleteById($id));
    }

    protected function toJson($data, int $status = ResponseAlias::HTTP_OK): JsonResponse
    {
        $this->context->setSuccess(true);

        return response()->json($data, $status);
    }

    protected function getService(): ServiceInterface
    {
        if (empty($this->service)) {
            throw new \RuntimeException('Service not set for controller');
        }

        return $this->service;
    }

    protected function getResourceClass(): string
    {
        if (empty($this->resourceClass)) {
            throw new \RuntimeException('ResourceClass not set for controller');
        }

        return $this->resourceClass;
    }
}
