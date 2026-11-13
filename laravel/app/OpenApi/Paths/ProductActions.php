<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class ProductActions
{
    #[OA\Post(
        path: '/api/products/archive',
        operationId: 'products.archive',
        description: 'Archive Product',
        summary: 'Archive to Product',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::ARCHIVE_PRODUCT_REQUEST
            )
        ),
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Archive Product',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function archive(): void {}

    #[OA\Post(
        path: '/api/products/link_book/{product_id}/{book_id}',
        operationId: 'products.link_book',
        description: 'Link Book to Product',
        summary: 'Link Book to Product',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(ref: Refs::PRODUCT_ID),
            new OA\Parameter(ref: Refs::BOOK_ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Link Book to Product',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function linkBook(): void {}

    #[OA\Post(
        path: '/api/products/link_source_product/{product_id}/{source_product_id}',
        operationId: 'products.link_source_product',
        description: 'Link SourceProduct to Product',
        summary: 'Link SourceProduct to Product',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(ref: Refs::PRODUCT_ID),
            new OA\Parameter(ref: Refs::SOURCE_PRODUCT_ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Link SourceProduct to Product',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function linkSourceProduct(): void {}

    #[OA\Post(
        path: '/api/products/unlink/{product_id}',
        operationId: 'products.unlink',
        description: 'Unlink from Product',
        summary: 'Unlink from Product',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(ref: Refs::PRODUCT_ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Unlink from Product',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function unlink(): void {}

    #[OA\Post(
        path: '/api/products/mass-save',
        operationId: 'products.mass_save',
        description: 'Mass Save Products',
        summary: 'Mass Save Products',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::MASS_SAVE_PRODUCT_REQUEST
            )
        ),
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Mass Save Products',
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function massSave(): void {}
}
