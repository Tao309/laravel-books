<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BindingTypes\StoreBindingTypeRequest;
use App\Http\Requests\BindingTypes\UpdateBindingTypeRequest;
use App\Services\BindingTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class BindingTypeController extends ApiController
{
    public function __construct(
        private readonly BindingTypeService $bindingTypeService,
    ) {}

    #[OA\Get(
        path: '/api/binding_types',
        description: 'Get list of binding types',
        summary: 'Get list of binding types',
        requestBody: new OA\RequestBody,
        tags: ['BindingTypes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of binding types',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BindingTypeCollection'
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->toJson(
            $this->bindingTypeService->getList()->toResourceCollection()
        );
    }

    #[OA\Post(
        path: '/api/binding_types',
        description: 'Create BindingType',
        summary: 'Create BindingType',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreBindingTypeRequest'
            )
        ),
        tags: ['BindingTypes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created BindingType',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BindingTypeResourceData'
                )
            ),
        ]
    )]
    public function store(StoreBindingTypeRequest $request): JsonResponse
    {
        return $this->toJson(
            $this->bindingTypeService->create($request->toDTO())->toResource(),
            Response::HTTP_CREATED
        );
    }

    #[OA\Patch(
        path: '/api/binding_types/{id}',
        description: 'Update BindingType by id',
        summary: 'Update BindingType by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateBindingTypeRequest'
            )
        ),
        tags: ['BindingTypes'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the BindingType',
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
                description: 'Update BindingType by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BindingTypeResourceData'
                )
            ),
        ]
    )]
    public function update(UpdateBindingTypeRequest $request, int $id): JsonResponse
    {
        return $this->toJson(
            $this->bindingTypeService->updateById($id, $request->toDTO())->toResource()
        );
    }

    #[OA\Delete(
        path: '/api/binding_types/{id}',
        description: 'Delete BindingType by id',
        summary: 'Delete BindingType by id',
        tags: ['BindingTypes'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Delete BindingType by id',
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
                description: 'Delete BindingType by id',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'boolean',
                            example: true,
                        ),
                    ]
                )
            ),
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        return $this->toJson([
            'success' => $this->bindingTypeService->deleteById($id),
        ]);
    }
}
