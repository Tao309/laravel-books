<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Products
{
    #[OA\Get(
        description: 'Get list of Products',
        operationId: 'products.index',
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        path: '/api/products',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PRODUCTS_PAGINATE_RESOURCE_COLLECTION
                ),
                description: 'Get list of Products',
                response: 200
            ),
        ],
        summary: 'Get list of Products',
        tags: ['Products']
    )]
    public function index(): void {}

    #[OA\Post(
        description: 'Create Product',
        operationId: 'products.store',
        path: '/api/products',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::STORE_PRODUCT_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                ),
                description: 'Get created Product',
                response: 201
            ),
        ],
        summary: 'Create Product',
        tags: ['Products']
    )]
    public function store(): void {}

    #[OA\Get(
        description: 'Get Product by id',
        operationId: 'products.show',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/products/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                ),
                description: 'Get Product by id',
                response: 200
            ),
        ],
        summary: 'Get Product by id',
        tags: ['Products']
    )]
    public function show(): void {}

    #[OA\Patch(
        description: 'Update Product by id',
        operationId: 'products.update',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/products/{id}',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::UPDATE_PRODUCT_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                ),
                description: 'Update Product by id',
                response: 200
            ),
        ],
        summary: 'Update Product by id',
        tags: ['Products']
    )]
    public function update(): void {}

    #[OA\Delete(
        description: 'Delete Product by id',
        operationId: 'products.destroy',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/products/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            example: true,
                            property: 'success',
                            type: 'boolean',
                        ),
                    ]
                ),
                description: 'Delete Product by id',
                response: 200
            ),
        ],
        summary: 'Delete Product by id',
        tags: ['Products']
    )]
    public function destroy(): void {}
}
