<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractResource extends JsonResource
{
    protected bool $toWrap = false;

    abstract public function getArrayData(Request $request): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->getArrayData($request);

        if ($this->toWrap) {
            return [
                'data' => $data,
            ];
        }

        return $data;
    }
}
