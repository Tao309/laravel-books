<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Brands
{
    #[OA\Get(
        path: '/api/brands',
        operationId: 'brands.index',
        description: 'Get list of brands',
        summary: 'Get list of brands',
        tags: ['Brands'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of brands',
                content: new OA\JsonContent(
                    ref: Refs::BRANDS_PAGINATE_RESOURCE_COLLECTION
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/brands',
        operationId: 'brands.store',
        description: 'Create Brand',
        summary: 'Create Brand',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_BRAND_REQUEST
            )
        ),
        tags: ['Brands'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created Brand',
                content: new OA\JsonContent(
                    ref: Refs::BRAND_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/brands/{id}',
        operationId: 'brands.show',
        description: 'Get brand by id',
        summary: 'Get brand by id',
        tags: ['Brands'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get brand by id',
                content: new OA\JsonContent(
                    ref: Refs::BRAND_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Patch(
        path: '/api/brands/{id}',
        operationId: 'brands.update',
        description: 'Update brand by id',
        summary: 'Update brand by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_BRAND_REQUEST
            )
        ),
        tags: ['Brands'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update brand by id',
                content: new OA\JsonContent(
                    ref: Refs::BRAND_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/brands/{id}',
        operationId: 'brands.destroy',
        description: 'Delete brand by id',
        summary: 'Delete brand by id',
        tags: ['Brands'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Delete brand by id',
                content: new OA\JsonContent(
                    ref: Refs::BRAND_DESTROY_RESPONSE
                )
            ),
        ]
    )]
    public function destroy(): void {}
}
