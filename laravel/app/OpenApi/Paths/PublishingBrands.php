<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class PublishingBrands
{
    #[OA\Get(
        path: '/api/publishing_brands',
        operationId: 'publishing_brands.index',
        description: 'Get list of Publishing Brands',
        summary: 'Get list of Publishing Brands',
        tags: ['PublishingBrands'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of Publishing Brands',
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_BRANDS_DATA_RESOURCE_COLLECTION
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/publishing_brands',
        operationId: 'publishing_brands.store',
        description: 'Create PublishingBrand',
        summary: 'Create PublishingBrand',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_PUBLISHING_BRAND_REQUEST
            )
        ),
        tags: ['PublishingBrands'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created PublishingBrand',
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_BRAND_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/publishing_brands/{id}',
        operationId: 'publishing_brands.show',
        description: 'Get PublishingBrand by id',
        summary: 'Get PublishingBrand by id',
        tags: ['PublishingBrands'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get PublishingBrand by id',
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_BRAND_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Patch(
        path: '/api/publishing_brands/{id}',
        operationId: 'publishing_brands.update',
        description: 'Update PublishingBrand by id',
        summary: 'Update PublishingBrand by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_PUBLISHING_BRAND_REQUEST
            )
        ),
        tags: ['PublishingBrands'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update PublishingBrand by id',
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_BRAND_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function update(): void {}
}
