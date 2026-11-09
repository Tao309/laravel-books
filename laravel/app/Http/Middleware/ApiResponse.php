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
            $data = $response->getData(true);

            if (is_array($data)) {
                if (! isset($data[self::DATA])) {
                    $data[self::DATA] = $data;
                }

                $data[self::SUCCESS] = true;

                $response->setData($data);
            }
        }

        return $response;
    }
}
