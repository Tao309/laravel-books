<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestInterface;
use App\Services\ServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

#[OA\Info(
    version: '1.0',
    description: 'Laravel Books API',
    title: 'Books API'
)]
abstract class ApiController extends Controller
{
    protected ServiceInterface $service;

    protected function indexAction(): JsonResponse
    {
        return $this->toJson(
            $this->getService()->getList()->toResourceCollection()
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
        return $this->toJson($this->service->getById($id)->toResource());
    }

    protected function updateAction(RequestInterface $request, int $id): JsonResponse
    {
        return $this->toJson(
            $this->getService()->updateById($id, $request->toDTO())->toResource()
        );
    }

    protected function destroyAction(int $id): JsonResponse
    {
        return $this->toJson([
            'success' => $this->getService()->deleteById($id),
        ]);
    }

    protected function toJson($data, int $status = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }

    protected function getService(): ServiceInterface
    {
        if (empty($this->service)) {
            throw new \RuntimeException('Service not set for controller');
        }

        return $this->service;
    }
}
