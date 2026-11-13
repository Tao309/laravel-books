<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class SourceProductTypes
{
    #[OA\Get(
        path: '/api/source_product_types',
        operationId: 'source_product_types.index',
        description: 'Get list of SourceProductTypes',
        summary: 'Get list of SourceProductTypes',
        tags: ['SourceProductTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of SourceProductTypes',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_TYPES_RESOURCE_COLLECTION
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/source_product_types',
        operationId: 'source_product_types.store',
        description: 'Create SourceProductType',
        summary: 'Create SourceProductType',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_SOURCE_PRODUCT_TYPE_REQUEST
            )
        ),
        tags: ['SourceProductTypes'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created SourceProductType',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_TYPE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/source_product_types/{id}',
        operationId: 'source_product_types.show',
        description: 'Get SourceProductType by id',
        summary: 'Get SourceProductType by id',
        tags: ['SourceProductTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get SourceProductType by id',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_TYPE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Patch(
        path: '/api/source_product_types/{id}',
        operationId: 'source_product_types.update',
        description: 'Update SourceProductType by id',
        summary: 'Update SourceProductType by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_SOURCE_PRODUCT_TYPE_REQUEST
            )
        ),
        tags: ['SourceProductTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update SourceProductType by id',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_TYPE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/source_product_types/{id}',
        operationId: 'source_product_types.destroy',
        description: 'Delete SourceProductType by id',
        summary: 'Delete SourceProductType by id',
        tags: ['SourceProductTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Delete SourceProductType by id',
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_TYPE_DESTROY_RESPONSE
                )
            ),
        ]
    )]
    public function destroy(): void {}
}
