<?php

namespace App\OpenApi\Schemas\Config;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ConfigResource',
    description: 'Application configuration',
    properties: [
        new OA\Property(property: 'processed_at', type: 'string', example: '2026-11-12 10:10:19'),
        new OA\Property(
            property: 'source_product_types',
            description: 'Source product types (id, code, name)',
            type: 'array',
            items: new OA\Items(type: 'object')
        ),
        new OA\Property(
            property: 'book_binding_types',
            description: 'Book binding types (id, label)',
            type: 'array',
            items: new OA\Items(type: 'object')
        ),
        new OA\Property(
            property: 'book_publishing_houses',
            type: 'array',
            items: new OA\Items(type: 'object')
        ),
        new OA\Property(
            property: 'book_publishing_brands',
            type: 'array',
            items: new OA\Items(type: 'object')
        ),
        new OA\Property(
            property: 'book_series',
            type: 'array',
            items: new OA\Items(type: 'object')
        ),
        new OA\Property(
            property: 'shop_urls',
            properties: [
                new OA\Property(property: 'ozon', type: 'string', example: 'https://www.ozon.ru'),
                new OA\Property(property: 'wildberries', type: 'string', example: 'https://www.wildberries.ru'),
                new OA\Property(property: 'chitai-gorod', type: 'string', example: 'https://www.chitai-gorod.ru'),
                new OA\Property(property: 'ffan', type: 'string', example: 'https://ffan.ru'),
                new OA\Property(property: 'knigofan', type: 'string', example: 'https://knigofan.ru'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: 'access_rights',
            properties: [
                new OA\Property(
                    property: 'product',
                    properties: [
                        new OA\Property(property: 'update', type: 'bool', example: true),
                        new OA\Property(property: 'create', type: 'bool', example: true),
                        new OA\Property(property: 'limit_enabled', type: 'bool', example: false),
                        new OA\Property(property: 'limit', type: 'integer', nullable: true),
                    ],
                    type: 'object'
                ),
                new OA\Property(
                    property: 'book',
                    properties: [
                        new OA\Property(property: 'update', type: 'bool', example: true),
                        new OA\Property(property: 'create', type: 'bool', example: true),
                        new OA\Property(property: 'limit', type: 'integer', nullable: true),
                        new OA\Property(property: 'add_publishing_house', type: 'bool', example: true),
                        new OA\Property(property: 'add_publishing_brand', type: 'bool', example: true),
                        new OA\Property(property: 'add_series', type: 'bool', example: true),
                    ],
                    type: 'object'
                ),
                new OA\Property(
                    property: 'source_product',
                    properties: [
                        new OA\Property(property: 'enabled', type: 'bool', example: true),
                        new OA\Property(property: 'create', type: 'bool', example: true),
                        new OA\Property(property: 'update', type: 'bool', example: true),
                        new OA\Property(property: 'limit', type: 'integer', nullable: true),
                    ],
                    type: 'object'
                ),
                new OA\Property(
                    property: 'shop',
                    properties: [
                        new OA\Property(
                            property: 'list',
                            type: 'array',
                            items: new OA\Items(type: 'string')
                        ),
                    ],
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
        new OA\Property(property: 'app_version', type: 'string', example: '1.0.0'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ConfigResponse',
    description: 'Application config response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::CONFIG_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class ConfigResourceSchema {}
