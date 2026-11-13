<?php

namespace App\OpenApi\Schemas\SourceProducts;

use App\Models\SourceProduct;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateSourceProductRequest',
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'Рубашка льняная с рукавами на лето'),

        new OA\Property(
            property: SourceProduct::PARAM_SOURCE_PRODUCT_TYPE,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
    ],
    type: 'object'
)]
class UpdateSourceProductRequestSchema {}
