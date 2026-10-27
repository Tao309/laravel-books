<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0',
    description: 'Laravel Books API',
    title: 'Books API'
)]
abstract class ApiController extends Controller
{
    public function json($data, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }
}
