<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class PublishingBrands
{
    #[OA\Get(
        description: 'Get list of Publishing Brands',
        operationId: 'publishing_brands.index',
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        path: '/api/publishing_brands',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_BRANDS_DATA_RESOURCE_COLLECTION
                ),
                description: 'Get list of Publishing Brands',
                response: 200
            ),
        ],
        summary: 'Get list of Publishing Brands',
        tags: ['PublishingBrands']
    )]
    public function index(): void {}

    #[OA\Post(
        description: 'Create PublishingBrand',
        operationId: 'publishing_brands.store',
        path: '/api/publishing_brands',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::STORE_PUBLISHING_BRAND_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_BRAND_DATA_RESOURCE
                ),
                description: 'Get created PublishingBrand',
                response: 201
            ),
        ],
        summary: 'Create PublishingBrand',
        tags: ['PublishingBrands']
    )]
    public function store(): void {}

    #[OA\Get(
        description: 'Get PublishingBrand by id',
        operationId: 'publishing_brands.show',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/publishing_brands/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_BRAND_DATA_RESOURCE
                ),
                description: 'Get PublishingBrand by id',
                response: 200
            ),
        ],
        summary: 'Get PublishingBrand by id',
        tags: ['PublishingBrands']
    )]
    public function show(): void {}

    #[OA\Patch(
        description: 'Update PublishingBrand by id',
        operationId: 'publishing_brands.update',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/publishing_brands/{id}',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::UPDATE_PUBLISHING_BRAND_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_BRAND_DATA_RESOURCE
                ),
                description: 'Update PublishingBrand by id',
                response: 200
            ),
        ],
        summary: 'Update PublishingBrand by id',
        tags: ['PublishingBrands']
    )]
    public function update(): void {}
}
