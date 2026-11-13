<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Products
{
    #[OA\Get(
        path: '/api/products',
        operationId: 'products.index',
        description: 'Get list of Products',
        summary: 'Get list of Products',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of Products',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCTS_INDEX_RESPONSE
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/products',
        operationId: 'products.store',
        description: 'Create Product',
        summary: 'Create Product',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_PRODUCT_REQUEST
            )
        ),
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created Product',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/products/{id}',
        operationId: 'products.show',
        description: 'Get Product by id',
        summary: 'Get Product by id',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get Product by id',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Patch(
        path: '/api/products/{id}',
        operationId: 'products.update',
        description: 'Update Product by id',
        summary: 'Update Product by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_PRODUCT_REQUEST
            )
        ),
        tags: ['Products'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update Product by id',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/products/{id}',
        operationId: 'products.destroy',
        description: 'Delete Product by id',
        summary: 'Delete Product by id',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Delete Product by id',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DESTROY_RESPONSE
                )
            ),
        ]
    )]
    public function destroy(): void {}
}
