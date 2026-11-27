<?php

namespace App\Http\Controllers\Api;

use App\DTOs\SourceProductsCollectionQueryDto;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\SourceProducts\SourceProductsByBrandCodeRequest;
use Illuminate\Http\JsonResponse;

class SourceProductsByBrandCodeController extends ApiController
{
    public function __invoke(
        SourceProductsByBrandCodeRequest $request,
        string $brandCode,
    ): JsonResponse {
        $result = $this->service->getList(
            SourceProductsCollectionQueryDto::fromArray([
                SourceProductsCollectionQueryDto::FIELD_BRAND_CODE => $brandCode,
                IndexRequest::FIELD_LIMIT => 50,
            ])
        );

        return $this->toJson(
            $result->count()
                ? $result->toResourceCollection()
                : $this->getEmptyCollectionResponse()
        );
    }
}
