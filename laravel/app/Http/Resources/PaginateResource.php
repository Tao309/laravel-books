<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginateResource extends JsonResource
{
    public function __construct($resource, public $resourceClass = null)
    {
        parent::__construct($resource);
    }

    public function collect($resource)
    {
        return $this->resourceClass::collection($resource);
    }

    public function toArray(Request $request): array|\JsonSerializable|Arrayable
    {
        return [
            'items' => $this->collect($this->items()),
            'links' => [
                'path' => $this->path(),
                'first_page_url' => $this->url(1),
                'last_page_url' => $this->url($this->lastPage()),
                'next_page_url' => $this->nextPageUrl(),
                'prev_page_url' => $this->previousPageUrl(),
            ],
            'meta' => [
                'current_page' => $this->currentPage(),
                'per_page' => $this->perPage(),
                'from' => $this->firstItem(),
                'last_page' => $this->lastPage(),
                'to' => $this->lastItem(),
                'total' => $this->total(),
                'count' => count($this->items()),
            ],
        ];
    }
}
