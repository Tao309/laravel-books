<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0',
    description: 'Laravel Books API',
    title: 'Books API'
)]
class Info {}
