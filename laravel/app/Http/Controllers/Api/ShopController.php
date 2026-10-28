<?php

namespace App\Http\Controllers\Api;

use App\Services\ShopService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShopController extends ApiController
{
    public function __construct(
        private readonly ShopService $shopService,
    ) {}

    #[OA\Get(
        path: '/api/shops',
        description: 'Get list of shops',
        summary: 'Get list of shops',
        requestBody: new OA\RequestBody,
        tags: ['Shops'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of shops',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ShopCollection'
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->toJson(
            $this->shopService->getList()->toResourceCollection()
        );
    }

    #[OA\Get(
        path: '/api/shops/{id}',
        description: 'Get shop by id',
        summary: 'Get shop by id',
        tags: ['Shops'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the shop',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 4,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get shop by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ShopDataResource'
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return $this->toJson($this->shopService->getById($id)->toResource());
    }
}
