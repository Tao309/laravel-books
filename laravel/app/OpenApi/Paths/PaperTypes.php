<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class PaperTypes
{
    #[OA\Get(
        description: 'Get list of PaperTypes',
        operationId: 'paper_types.index',
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        path: '/api/paper_types',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PAPER_TYPES_DATA_RESOURCE_COLLECTION
                ),
                description: 'Get list of PaperTypes',
                response: 200
            ),
        ],
        summary: 'Get list of PaperTypes',
        tags: ['PaperTypes']
    )]
    public function index(): void {}

    #[OA\Post(
        description: 'Create PaperType',
        operationId: 'paper_types.store',
        path: '/api/paper_types',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::STORE_PAPER_TYPE_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PAPER_TYPE_DATA_RESOURCE
                ),
                description: 'Get created PaperType',
                response: 201
            ),
        ],
        summary: 'Create PaperType',
        tags: ['PaperTypes']
    )]
    public function store(): void {}

    #[OA\Get(
        description: 'Get PaperType by id',
        operationId: 'paper_types.show',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/paper_types/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PAPER_TYPE_DATA_RESOURCE
                ),
                description: 'Get PaperType by id',
                response: 200
            ),
        ],
        summary: 'Get PaperType by id',
        tags: ['PaperTypes']
    )]
    public function show(): void {}

    #[OA\Patch(
        description: 'Update PaperType by id',
        operationId: 'paper_types.update',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/paper_types/{id}',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::UPDATE_PAPER_TYPE_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PAPER_TYPE_DATA_RESOURCE
                ),
                description: 'Update PaperType by id',
                response: 200
            ),
        ],
        summary: 'Update PaperType by id',
        tags: ['PaperTypes']
    )]
    public function update(): void {}

    #[OA\Delete(
        description: 'Delete PaperType by id',
        operationId: 'paper_types.destroy',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/paper_types/{id}',
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
                description: 'Delete PaperType by id',
                response: 200
            ),
        ],
        summary: 'Delete PaperType by id',
        tags: ['PaperTypes']
    )]
    public function destroy(): void {}
}
