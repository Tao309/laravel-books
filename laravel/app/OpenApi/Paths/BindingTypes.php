<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class BindingTypes
{
    #[OA\Get(
        path: '/api/binding_types',
        operationId: 'binding_types.index',
        description: 'Get list of binding types',
        summary: 'Get list of binding types',
        tags: ['BindingTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of binding types',
                content: new OA\JsonContent(
                    ref: Refs::BINDING_TYPES_INDEX_RESPONSE
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/binding_types',
        operationId: 'binding_types.store',
        description: 'Create BindingType',
        summary: 'Create BindingType',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_BINDING_TYPE_REQUEST
            )
        ),
        tags: ['BindingTypes'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created BindingType',
                content: new OA\JsonContent(
                    ref: Refs::BINDING_TYPE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Patch(
        path: '/api/binding_types/{id}',
        operationId: 'binding_types.update',
        description: 'Update BindingType by id',
        summary: 'Update BindingType by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_BINDING_TYPE_REQUEST
            )
        ),
        tags: ['BindingTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update BindingType by id',
                content: new OA\JsonContent(
                    ref: Refs::BINDING_TYPE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/binding_types/{id}',
        operationId: 'binding_types.destroy',
        description: 'Delete BindingType by id',
        summary: 'Delete BindingType by id',
        tags: ['BindingTypes'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Delete BindingType by id',
                content: new OA\JsonContent(
                    ref: Refs::BINDING_TYPE_DESTROY_RESPONSE
                )
            ),
        ]
    )]
    public function destroy(): void {}
}
