<?php

namespace App\OpenApi\Schemas\Config;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Application configuration',
    properties: [
        new OA\Property(example: '2026-11-12 10:10:19', property: 'processed_at', type: 'string'),
        new OA\Property(
            description: 'Source product types (id, code, name)',
            items: new OA\Items(type: 'object'),
            property: 'source_product_types',
            type: 'array'
        ),
        new OA\Property(
            description: 'Book binding types (id, label)',
            items: new OA\Items(type: 'object'),
            property: 'book_binding_types',
            type: 'array'
        ),
        new OA\Property(
            items: new OA\Items(type: 'object'),
            property: 'book_publishing_houses',
            type: 'array'
        ),
        new OA\Property(
            items: new OA\Items(type: 'object'),
            property: 'book_publishing_brands',
            type: 'array'
        ),
        new OA\Property(
            items: new OA\Items(type: 'object'),
            property: 'book_series',
            type: 'array'
        ),
        new OA\Property(
            properties: [
                new OA\Property(example: 'https://www.ozon.ru', property: 'ozon', type: 'string'),
                new OA\Property(example: 'https://www.wildberries.ru', property: 'wildberries', type: 'string'),
                new OA\Property(example: 'https://www.chitai-gorod.ru', property: 'chitai-gorod', type: 'string'),
                new OA\Property(example: 'https://ffan.ru', property: 'ffan', type: 'string'),
                new OA\Property(example: 'https://knigofan.ru', property: 'knigofan', type: 'string'),
            ],
            property: 'shop_urls',
            type: 'object'
        ),
        new OA\Property(
            properties: [
                new OA\Property(
                    properties: [
                        new OA\Property(example: true, property: 'update', type: 'bool'),
                        new OA\Property(example: true, property: 'create', type: 'bool'),
                        new OA\Property(example: false, property: 'limit_enabled', type: 'bool'),
                        new OA\Property(nullable: true, property: 'limit', type: 'integer'),
                    ],
                    property: 'product',
                    type: 'object'
                ),
                new OA\Property(
                    properties: [
                        new OA\Property(example: true, property: 'update', type: 'bool'),
                        new OA\Property(example: true, property: 'create', type: 'bool'),
                        new OA\Property(nullable: true, property: 'limit', type: 'integer'),
                        new OA\Property(example: true, property: 'add_publishing_house', type: 'bool'),
                        new OA\Property(example: true, property: 'add_publishing_brand', type: 'bool'),
                        new OA\Property(example: true, property: 'add_series', type: 'bool'),
                    ],
                    property: 'book',
                    type: 'object'
                ),
                new OA\Property(
                    properties: [
                        new OA\Property(example: true, property: 'enabled', type: 'bool'),
                        new OA\Property(example: true, property: 'create', type: 'bool'),
                        new OA\Property(example: true, property: 'update', type: 'bool'),
                        new OA\Property(nullable: true, property: 'limit', type: 'integer'),
                    ],
                    property: 'source_product',
                    type: 'object'
                ),
                new OA\Property(
                    properties: [
                        new OA\Property(
                            items: new OA\Items(type: 'string'),
                            property: 'list',
                            type: 'array'
                        ),
                    ],
                    property: 'shop',
                    type: 'object'
                ),
            ],
            property: 'access_rights',
            type: 'object'
        ),
        new OA\Property(example: '1.0.0', property: 'app_version', type: 'string'),
    ],
    schema: 'ConfigResource',
    type: 'object'
)]
class ConfigResourceSchema {}
