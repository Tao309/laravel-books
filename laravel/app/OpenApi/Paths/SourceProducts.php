<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class SourceProducts
{
    #[OA\Get(
        description: 'Get list of SourceProducts',
        operationId: 'source_products.index',
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        path: '/api/source_products',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCTS_DATA_RESOURCE_COLLECTION
                ),
                description: 'Get list of SourceProducts',
                response: 200
            ),
        ],
        summary: 'Get list of SourceProducts',
        tags: ['SourceProducts']
    )]
    public function index(): void {}

    #[OA\Post(
        description: 'Create SourceProduct',
        operationId: 'source_products.store',
        path: '/api/source_products',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::STORE_SOURCE_PRODUCT_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_DATA_RESOURCE
                ),
                description: 'Get created SourceProduct',
                response: 201
            ),
        ],
        summary: 'Create SourceProduct',
        tags: ['SourceProducts']
    )]
    public function store(): void {}

    #[OA\Get(
        description: 'Get SourceProduct by id',
        operationId: 'source_products.show',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/source_products/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_DATA_RESOURCE
                ),
                description: 'Get SourceProduct by id',
                response: 200
            ),
        ],
        summary: 'Get SourceProduct by id',
        tags: ['SourceProducts']
    )]
    public function show(): void {}

    #[OA\Patch(
        description: 'Update SourceProduct by id',
        operationId: 'source_products.update',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/source_products/{id}',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::UPDATE_SOURCE_PRODUCT_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_DATA_RESOURCE
                ),
                description: 'Update SourceProduct by id',
                response: 200
            ),
        ],
        summary: 'Update SourceProduct by id',
        tags: ['SourceProducts']
    )]
    public function update(): void {}

    #[OA\Delete(
        description: 'Delete SourceProduct by id',
        operationId: 'source_products.destroy',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/source_products/{id}',
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
                description: 'Delete SourceProduct by id',
                response: 200
            ),
        ],
        summary: 'Delete SourceProduct by id',
        tags: ['SourceProducts']
    )]
    public function destroy(): void {}
}
