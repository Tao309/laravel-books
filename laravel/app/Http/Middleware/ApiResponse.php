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

    public const string MESSAGE = 'message';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $responseData = $response->getData(true);

            if (is_array($responseData)) {
                if (isset($responseData[self::SUCCESS]) && ! $responseData[self::SUCCESS]) {
                    $response->setData($responseData);

                    return $response;
                }

                $data = [];

                $message = $responseData[self::MESSAGE] ?? null;
                unset($responseData[self::MESSAGE]);

                if (! isset($responseData[self::DATA])) {
                    $data[self::DATA] = $responseData;
                } else {
                    $data = $responseData;
                }

                if (! isset($data[self::SUCCESS])) {
                    $data[self::SUCCESS] = true;
                }

                $data[self::MESSAGE] = $message;

                $response->setData($data);
            }
        }

        return $response;
    }
}
