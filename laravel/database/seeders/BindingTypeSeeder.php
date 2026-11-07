<?php

namespace Database\Seeders;

use App\Models\BindingType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BindingTypeSeeder extends AbstractSeeder
{
    public function run(): void
    {
        BindingType::unguard();

        DB::table(BindingType::TABLE_NAME)->insert([
            [
                BindingType::PARAM_ID => 1,
                BindingType::PARAM_LABEL => 'Твёрдый переплёт',
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
            [
                BindingType::PARAM_ID => 2,
                BindingType::PARAM_LABEL => 'Мягкий переплёт',
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
        ]);

        $this->updateLastId(BindingType::TABLE_NAME);
    }
}
