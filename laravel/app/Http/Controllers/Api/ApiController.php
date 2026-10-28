<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

#[OA\Info(
    version: '1.0',
    description: 'Laravel Books API',
    title: 'Books API'
)]
abstract class ApiController extends Controller
{
    public function toJson($data, int $status = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }
}
