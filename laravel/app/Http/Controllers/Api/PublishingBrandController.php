<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PublishingBrands\StorePublishingBrandRequest;
use App\Http\Requests\PublishingBrands\UpdatePublishingBrandRequest;
use App\Services\PublishingBrandService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PublishingBrandController extends ApiController
{
    public function __construct(
        PublishingBrandService $service,
    ) {
        $this->service = $service;
    }

    #[OA\Get(
        path: '/api/publishing_brands',
        description: 'Get list of Publishing Brands',
        summary: 'Get list of Publishing Brands',
        requestBody: new OA\RequestBody,
        tags: ['PublishingBrands'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of Publishing Brands',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PublishingBrandResourceCollection'
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->indexAction();
    }

    #[OA\Post(
        path: '/api/publishing_brands',
        description: 'Create PublishingBrand',
        summary: 'Create PublishingBrand',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StorePublishingBrandRequest'
            )
        ),
        tags: ['PublishingBrands'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created PublishingBrand',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PublishingBrandDataResource'
                )
            ),
        ]
    )]
    public function store(StorePublishingBrandRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    #[OA\Get(
        path: '/api/publishing_brands/{id}',
        description: 'Get PublishingBrand by id',
        summary: 'Get PublishingBrand by id',
        tags: ['PublishingBrands'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the PublishingBrand',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 4,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get PublishingBrand by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PublishingBrandDataResource'
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    #[OA\Patch(
        path: '/api/publishing_brands/{id}',
        description: 'Update PublishingBrand by id',
        summary: 'Update PublishingBrand by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdatePublishingBrandRequest'
            )
        ),
        tags: ['PublishingBrands'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the PublishingBrand',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 4,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update PublishingBrand by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PublishingBrandDataResource'
                )
            ),
        ]
    )]
    public function update(UpdatePublishingBrandRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }
}
