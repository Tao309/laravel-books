<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public const string DATA = 'data';

    public const string SUCCESS = 'success';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $responseData = $response->getData(true);

            if (is_array($responseData)) {
                $data = [];

                if (! isset($responseData[self::DATA])) {
                    $data[self::DATA] = $responseData;
                } else {
                    $data = $responseData;
                }

                $data[self::SUCCESS] = true;

                $response->setData($data);
            }
        }

        return $response;
    }
}
