<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class SourceProducts
{
    #[OA\Get(
        path: '/api/source_products',
        operationId: 'source_products.index',
        description: 'Get list of SourceProducts',
        summary: 'Get list of SourceProducts',
        tags: ['SourceProducts'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of SourceProducts',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_TYPES_INDEX_RESPONSE
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/source_products',
        operationId: 'source_products.store',
        description: 'Create SourceProduct',
        summary: 'Create SourceProduct',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_SOURCE_PRODUCT_REQUEST
            )
        ),
        tags: ['SourceProducts'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created SourceProduct',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/source_products/{id}',
        operationId: 'source_products.show',
        description: 'Get SourceProduct by id',
        summary: 'Get SourceProduct by id',
        tags: ['SourceProducts'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get SourceProduct by id',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Patch(
        path: '/api/source_products/{id}',
        operationId: 'source_products.update',
        description: 'Update SourceProduct by id',
        summary: 'Update SourceProduct by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_SOURCE_PRODUCT_REQUEST
            )
        ),
        tags: ['SourceProducts'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update SourceProduct by id',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/source_products/{id}',
        operationId: 'source_products.destroy',
        description: 'Delete SourceProduct by id',
        summary: 'Delete SourceProduct by id',
        tags: ['SourceProducts'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Delete SourceProduct by id',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_DESTROY_RESPONSE
                )
            ),
        ]
    )]
    public function destroy(): void {}
}
