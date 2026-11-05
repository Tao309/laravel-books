<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\Products\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductController extends ApiController
{
    protected string $resourceClass = ProductResource::class;

    public function __construct(
        ProductService $service,
    ) {
        $this->service = $service;
    }

    #[OA\Get(
        path: '/api/products',
        description: 'Get list of Products',
        summary: 'Get list of Products',
        requestBody: new OA\RequestBody,
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of Products',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductsResourceCollection'
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->indexAction();
    }

    #[OA\Post(
        path: '/api/products',
        description: 'Create Product',
        summary: 'Create Product',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreProductRequest'
            )
        ),
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created Product',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductDataResource'
                )
            ),
        ]
    )]
    public function store(StoreProductRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    #[OA\Get(
        path: '/api/products/{id}',
        description: 'Get Product by id',
        summary: 'Get Product by id',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the Product',
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
                description: 'Get Product by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductDataResource'
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    #[OA\Patch(
        path: '/api/products/{id}',
        description: 'Update Product by id',
        summary: 'Update Product by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateProductRequest'
            )
        ),
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the Product',
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
                description: 'Update Product by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductDataResource'
                )
            ),
        ]
    )]
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    #[OA\Delete(
        path: '/api/products/{id}',
        description: 'Delete Product by id',
        summary: 'Delete Product by id',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Delete Product by id',
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
                description: 'Delete Product by id',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'boolean',
                            example: true,
                        ),
                    ]
                )
            ),
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        return $this->destroyAction($id);
    }
}
