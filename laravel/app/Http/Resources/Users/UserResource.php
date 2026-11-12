<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\AbstractResource;
use App\Models\User;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
