<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\AbstractResource;
use App\Models\User;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: '1'),
        new OA\Property(property: 'name', type: 'string', example: 'admin'),
        new OA\Property(property: 'email', type: 'string', example: 'admin@mail.ru'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
class UserResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            User::PARAM_ID => $this->offsetGet(User::PARAM_ID),
            User::PARAM_NAME => $this->offsetGet(User::PARAM_NAME),
            User::PARAM_EMAIL => $this->offsetGet(User::PARAM_EMAIL),
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
        ];
    }
}
