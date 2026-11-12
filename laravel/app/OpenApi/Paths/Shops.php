<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Shops
{
    #[OA\Get(
        description: 'Get list of shops',
        operationId: 'shops.index',
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        path: '/api/shops',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SHOPS_DATA_RESOURCE_COLLECTION
                ),
                description: 'Get list of shops',
                response: 200
            ),
        ],
        summary: 'Get list of shops',
        tags: ['Shops']
    )]
    public function index(): void {}

    #[OA\Get(
        description: 'Get shop by id',
        operationId: 'shops.show',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/shops/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::SHOP_DATA_RESOURCE
                ),
                description: 'Get shop by id',
                response: 200
            ),
        ],
        summary: 'Get shop by id',
        tags: ['Shops']
    )]
    public function show(): void {}
}
