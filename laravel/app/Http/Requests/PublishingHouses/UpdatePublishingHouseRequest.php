<?php

namespace App\Http\Requests\PublishingHouses;

use App\Http\Requests\RequestInterface;
use App\Models\PublishingHouse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdatePublishingHouseRequest extends AbstractPublishingHouseRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('publishing_house');

        return [
            PublishingHouse::PARAM_NAME => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':255',
                Rule::unique(PublishingHouse::TABLE_NAME)->ignore($id),
            ],
        ];
    }
}
