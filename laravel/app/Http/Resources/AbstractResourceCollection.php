<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

abstract class AbstractResourceCollection extends ResourceCollection
{
    protected bool $toWrap = false;

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array|Collection
    {
        if ($this->toWrap) {
            return [
                'count' => $this->collection->count(),
                'data' => $this->collection,
                'success' => true,
            ];
        }

        return $this->collection;
    }
}
