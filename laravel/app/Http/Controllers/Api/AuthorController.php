<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Authors\StoreAuthorRequest;
use App\Http\Requests\Authors\UpdateAuthorRequest;
use App\Services\AuthorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class AuthorController extends ApiController
{
    public function __construct(
        private readonly AuthorService $authorService,
    ) {}

    #[OA\Get(
        path: '/api/authors',
        description: 'Get list of authors',
        summary: 'Get list of authors',
        requestBody: new OA\RequestBody,
        tags: ['Authors'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of authors',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/AuthorCollection'
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->json(
            $this->authorService->getListPaginate()
        );
    }

    #[OA\Post(
        path: '/api/authors',
        description: 'Create Author',
        summary: 'Create Author',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreAuthorRequest'
            )
        ),
        tags: ['Authors'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created Author',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/AuthorResourceData'
                )
            ),
        ]
    )]
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        return $this->json(
            $this->authorService->create($request->validated()),
            Response::HTTP_CREATED
        );
    }

    #[OA\Get(
        path: '/api/authors/{id}',
        description: 'Get author by id',
        summary: 'Get author by id',
        tags: ['Authors'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the author',
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
                description: 'Get author by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/AuthorResourceData'
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return $this->json($this->authorService->getById($id));
    }

    #[OA\Patch(
        path: '/api/authors/{id}',
        description: 'Update author by id',
        summary: 'Update author by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateAuthorRequest'
            )
        ),
        tags: ['Authors'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the author',
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
                description: 'Update author by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/AuthorResourceData'
                )
            ),
        ]
    )]
    public function update(UpdateAuthorRequest $request, int $id): JsonResponse
    {
        return $this->json(
            $this->authorService->updateById($id, $request->validated())

        );
    }

    #[OA\Delete(
        path: '/api/authors/{id}',
        description: 'Delete author by id',
        summary: 'Delete author by id',
        tags: ['Authors'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Delete author by id',
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
                description: 'Delete author by id',
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
        return $this->json([
            'success' => $this->authorService->deleteById($id),
        ]);
    }
}
