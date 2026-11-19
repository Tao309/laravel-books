<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\AbstractResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MassSaveResourceCollection extends AbstractResourceCollection
{
    public $collects = ProductResource::class;

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array|Collection
    {
        return [
            'items' => $this->collection,
            'meta' => [
                'count' => $this->collection->count(),
            ],
            ...$this->additional,
        ];
    }
}
