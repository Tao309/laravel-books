<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractResource extends JsonResource
{
    abstract public function getArrayData(Request $request): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->getArrayData($request);
    }
}
