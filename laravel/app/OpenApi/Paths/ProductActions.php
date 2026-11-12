<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class ProductActions
{
    #[OA\Post(
        description: 'Archive Product',
        operationId: 'products.archive',
        path: '/api/products/archive',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::ARCHIVE_PRODUCT_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                ),
                description: 'Archive Product',
                response: 200
            ),
        ],
        summary: 'Archive to Product',
        tags: ['Products']
    )]
    public function archive(): void {}

    #[OA\Post(
        description: 'Link Book to Product',
        operationId: 'products.link_book',
        parameters: [
            new OA\Parameter(ref: Refs::PRODUCT_ID),
            new OA\Parameter(ref: Refs::BOOK_ID),
        ],
        path: '/api/products/link_book/{product_id}/{book_id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                ),
                description: 'Link Book to Product',
                response: 200
            ),
        ],
        summary: 'Link Book to Product',
        tags: ['Products']
    )]
    public function linkBook(): void {}

    #[OA\Post(
        description: 'Link SourceProduct to Product',
        operationId: 'products.link_source_product',
        parameters: [
            new OA\Parameter(ref: Refs::PRODUCT_ID),
            new OA\Parameter(ref: Refs::SOURCE_PRODUCT_ID),
        ],
        path: '/api/products/link_source_product/{product_id}/{source_product_id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                ),
                description: 'Link SourceProduct to Product',
                response: 200
            ),
        ],
        summary: 'Link SourceProduct to Product',
        tags: ['Products']
    )]
    public function linkSourceProduct(): void {}

    #[OA\Post(
        description: 'Unlink from Product',
        operationId: 'products.unlink',
        parameters: [
            new OA\Parameter(ref: Refs::PRODUCT_ID),
        ],
        path: '/api/products/unlink/{product_id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                ),
                description: 'Unlink from Product',
                response: 200
            ),
        ],
        summary: 'Unlink from Product',
        tags: ['Products']
    )]
    public function unlink(): void {}

    #[OA\Post(
        description: 'Mass Save Products',
        operationId: 'products.mass_save',
        path: '/api/products/mass-save',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::MASS_SAVE_PRODUCT_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PRODUCT_DATA_RESOURCE
                ),
                description: 'Mass Save Products',
                response: 200
            ),
        ],
        summary: 'Mass Save Products',
        tags: ['Products']
    )]
    public function massSave(): void {}
}
