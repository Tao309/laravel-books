<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PublishingHouses\StorePublishingHouseRequest;
use App\Http\Requests\PublishingHouses\UpdatePublishingHouseRequest;
use App\Services\PublishingHouseService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PublishingHouseController extends ApiController
{
    public function __construct(
        private readonly PublishingHouseService $service,
    ) {}

    #[OA\Get(
        path: '/api/publishing_houses',
        description: 'Get list of Publishing Houses',
        summary: 'Get list of Publishing Houses',
        requestBody: new OA\RequestBody,
        tags: ['PublishingHouses'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of Publishing Houses',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PublishingHouseResourceCollection'
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->toJson(
            $this->service->getList()->toResourceCollection()
        );
    }

    #[OA\Post(
        path: '/api/publishing_houses',
        description: 'Create PublishingHouse',
        summary: 'Create PublishingHouse',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StorePublishingHouseRequest'
            )
        ),
        tags: ['PublishingHouses'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created PublishingHouse',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PublishingHouseDataResource'
                )
            ),
        ]
    )]
    public function store(StorePublishingHouseRequest $request): JsonResponse
    {
        return $this->toJson(
            $this->service->create($request->validated())->toResource(),
            ResponseAlias::HTTP_CREATED
        );
    }

    #[OA\Get(
        path: '/api/publishing_houses/{id}',
        description: 'Get PublishingHouse by id',
        summary: 'Get PublishingHouse by id',
        tags: ['PublishingHouses'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the PublishingHouse',
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
                description: 'Get PublishingHouse by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PublishingHouseDataResource'
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return $this->toJson($this->service->getById($id)->toResource());
    }

    #[OA\Patch(
        path: '/api/publishing_houses/{id}',
        description: 'Update PublishingHouse by id',
        summary: 'Update PublishingHouse by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdatePublishingHouseRequest'
            )
        ),
        tags: ['PublishingHouses'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the PublishingHouse',
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
                description: 'Update PublishingHouse by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PublishingHouseDataResource'
                )
            ),
        ]
    )]
    public function update(UpdatePublishingHouseRequest $request, int $id): JsonResponse
    {
        return $this->toJson(
            $this->service->updateById($id, $request->validated())->toResource()
        );
    }
}
