<?php

namespace App\Http\Middleware;

use App\Http\Response\ResponseContext;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class ApiResponse
{
    public function __construct(
        private ResponseContext $context
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $this->processResponse($response);

        return $response;
    }

    private function processResponse($response): void
    {
        if (! $response instanceof JsonResponse) {
            return;
        }

        $data = $response->getData(true);

        if (! is_array($data)) {
            return;
        }

        $data = [
            ResponseContext::DATA => $data,
        ];

        if ($this->context->getMessage()) {
            $data[ResponseContext::MESSAGE] = $this->context->getMessage();
        }

        $data[ResponseContext::SUCCESS] = $this->context->isSuccess();

        $response->setData($data);
    }
}
