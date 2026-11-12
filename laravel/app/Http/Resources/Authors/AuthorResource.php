<?php

namespace App\Http\Resources\Authors;

use App\Http\Resources\AbstractResource;
use App\Models\Author;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuthorResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            Author::PARAM_ID => $this->id,
            Author::PARAM_FIRST_NAME => $this->first_name,
            Author::PARAM_LAST_NAME => $this->last_name,
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
