<?php

namespace App\Http\Controllers\Api;

use App\Repositories\SourceProductUserDataRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SourceProductUserDataController extends ApiController
{
    public function __construct(
        private readonly SourceProductUserDataRepository $repository,
    ) {}

    #[OA\Get(
        path: '/api/source_product_user_data/{source_product_id}',
        description: 'Get SourceProduct by id',
        summary: 'Get SourceProduct by Book ID',
        tags: ['SourceProductUserData'],
        parameters: [
            new OA\Parameter(
                parameter: 'source_product_id',
                name: 'source_product_id',
                description: 'Id of the Book',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 2,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get SourceProduct by source_product_id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/SourceProductUserDataResource'
                )
            ),
        ]
    )]
    public function show(int $sourceProductId): JsonResponse
    {
        return $this->toJson($this->repository->get($sourceProductId)->toResource());
    }
}
