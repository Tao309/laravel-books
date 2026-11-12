<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    description: 'Laravel Books API',
    title: 'Books API',
    version: '1.0'
)]
class Info {}
