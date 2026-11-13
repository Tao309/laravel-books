<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Shops
{
    #[OA\Get(
        path: '/api/shops',
        operationId: 'shops.index',
        description: 'Get list of shops',
        summary: 'Get list of shops',
        tags: ['Shops'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of shops',
                content: new OA\JsonContent(
                    ref: Refs::SHOPS_INDEX_RESPONSE
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Get(
        path: '/api/shops/{id}',
        operationId: 'shops.show',
        description: 'Get shop by id',
        summary: 'Get shop by id',
        tags: ['Shops'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get shop by id',
                content: new OA\JsonContent(
                    ref: Refs::SHOP_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}
}
