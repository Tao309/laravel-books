<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Books\StoreBookRequest;
use App\Http\Requests\Books\UpdateBookRequest;
use App\Http\Resources\Books\BookResource;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class BookController extends ApiController
{
    protected string $resourceClass = BookResource::class;

    public function __construct(
        BookService $service,
    ) {
        $this->service = $service;
    }

    #[OA\Get(
        path: '/api/books',
        description: 'Get list of books',
        summary: 'Get list of books',
        requestBody: new OA\RequestBody,
        tags: ['Books'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of books',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BooksPaginateResourceCollection'
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->indexActionPaginate();
    }

    #[OA\Post(
        path: '/api/books',
        description: 'Create Book',
        summary: 'Create Book',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreBookRequest'
            )
        ),
        tags: ['Books'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created Book',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BookDataResource'
                )
            ),
        ]
    )]
    public function store(StoreBookRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    #[OA\Get(
        path: '/api/books/{id}',
        description: 'Get Book by id',
        summary: 'Get Book by id',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the Book',
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
                description: 'Get Book by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BookDataResource'
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    #[OA\Patch(
        path: '/api/books/{id}',
        description: 'Update Book by id',
        summary: 'Update Book by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateBookRequest'
            )
        ),
        tags: ['Books'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the Book',
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
                description: 'Update Book by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BookDataResource'
                )
            ),
        ]
    )]
    public function update(UpdateBookRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    #[OA\Delete(
        path: '/api/books/{id}',
        description: 'Delete Book by id',
        summary: 'Delete Book by id',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Delete Book by id',
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
                description: 'Delete Book by id',
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
        return $this->destroyAction($id);
    }
}
