<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class SourceProductTypes
{
    #[OA\Get(
        description: 'Get list of SourceProductTypes',
        operationId: 'source_product_types.index',
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        path: '/api/source_product_types',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_TYPES_DATA_RESOURCE_COLLECTION
                ),
                description: 'Get list of SourceProductTypes',
                response: 200
            ),
        ],
        summary: 'Get list of SourceProductTypes',
        tags: ['SourceProductTypes']
    )]
    public function index(): void {}

    #[OA\Post(
        description: 'Create SourceProductType',
        operationId: 'source_product_types.store',
        path: '/api/source_product_types',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::STORE_SOURCE_PRODUCT_TYPE_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_TYPE_DATA_RESOURCE
                ),
                description: 'Get created SourceProductType',
                response: 201
            ),
        ],
        summary: 'Create SourceProductType',
        tags: ['SourceProductTypes']
    )]
    public function store(): void {}

    #[OA\Get(
        description: 'Get SourceProductType by id',
        operationId: 'source_product_types.show',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/source_product_types/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_TYPE_DATA_RESOURCE
                ),
                description: 'Get SourceProductType by id',
                response: 200
            ),
        ],
        summary: 'Get SourceProductType by id',
        tags: ['SourceProductTypes']
    )]
    public function show(): void {}

    #[OA\Patch(
        description: 'Update SourceProductType by id',
        operationId: 'source_product_types.update',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/source_product_types/{id}',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::UPDATE_SOURCE_PRODUCT_TYPE_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SOURCE_PRODUCT_TYPE_DATA_RESOURCE
                ),
                description: 'Update SourceProductType by id',
                response: 200
            ),
        ],
        summary: 'Update SourceProductType by id',
        tags: ['SourceProductTypes']
    )]
    public function update(): void {}

    #[OA\Delete(
        description: 'Delete SourceProductType by id',
        operationId: 'source_product_types.destroy',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/source_product_types/{id}',
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
                description: 'Delete SourceProductType by id',
                response: 200
            ),
        ],
        summary: 'Delete SourceProductType by id',
        tags: ['SourceProductTypes']
    )]
    public function destroy(): void {}
}
