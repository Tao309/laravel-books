<?php

namespace App\OpenApi\Schemas\SourceProducts;

use App\Models\SourceProduct;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 'Рубашка льняная с рукавами на лето', property: 'title', type: 'string'),

        new OA\Property(
            properties: [
                new OA\Property(example: '1', property: 'id', type: 'integer'),
            ],
            property: SourceProduct::PARAM_SOURCE_PRODUCT_TYPE,
            type: 'object'
        ),
    ],
    schema: 'UpdateSourceProductRequest',
    type: 'object'
)]
class UpdateSourceProductRequestSchema {}
