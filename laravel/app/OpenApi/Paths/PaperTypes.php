<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class PaperTypes
{
    #[OA\Get(
        path: '/api/paper_types',
        operationId: 'paper_types.index',
        description: 'Get list of PaperTypes',
        summary: 'Get list of PaperTypes',
        tags: ['PaperTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of PaperTypes',
                content: new OA\JsonContent(
                    ref: Refs::PAPER_TYPES_INDEX_RESPONSE
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/paper_types',
        operationId: 'paper_types.store',
        description: 'Create PaperType',
        summary: 'Create PaperType',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_PAPER_TYPE_REQUEST
            )
        ),
        tags: ['PaperTypes'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created PaperType',
                content: new OA\JsonContent(
                    ref: Refs::PAPER_TYPE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/paper_types/{id}',
        operationId: 'paper_types.show',
        description: 'Get PaperType by id',
        summary: 'Get PaperType by id',
        tags: ['PaperTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get PaperType by id',
                content: new OA\JsonContent(
                    ref: Refs::PAPER_TYPE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Patch(
        path: '/api/paper_types/{id}',
        operationId: 'paper_types.update',
        description: 'Update PaperType by id',
        summary: 'Update PaperType by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_PAPER_TYPE_REQUEST
            )
        ),
        tags: ['PaperTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update PaperType by id',
                content: new OA\JsonContent(
                    ref: Refs::PAPER_TYPE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/paper_types/{id}',
        operationId: 'paper_types.destroy',
        description: 'Delete PaperType by id',
        summary: 'Delete PaperType by id',
        tags: ['PaperTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Delete PaperType by id',
                content: new OA\JsonContent(
                    ref: Refs::PAPER_TYPE_DESTROY_RESPONSE
                )
            ),
        ]
    )]
    public function destroy(): void {}
}
