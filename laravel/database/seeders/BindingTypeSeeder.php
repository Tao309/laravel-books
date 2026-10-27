<?php

namespace Database\Seeders;

use App\Models\BindingType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BindingTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('binding_types')->insert([
            BindingType::LABEL => 'Твёрдый переплёт',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('binding_types')->insert([
            BindingType::LABEL => 'Мягкий переплёт',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);
    }
}
