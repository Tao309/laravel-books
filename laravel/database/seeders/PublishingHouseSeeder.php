<?php

namespace Database\Seeders;

use App\Models\PublishingHouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublishingHouseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('publishing_houses')->insert([
            PublishingHouse::NAME => 'АСТ',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('publishing_houses')->insert([
            PublishingHouse::NAME => 'Эксмо',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('publishing_houses')->insert([
            PublishingHouse::NAME => 'Манн, Иванов и Фербер',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('publishing_houses')->insert([
            PublishingHouse::NAME => 'Альпина Паблишер',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('publishing_houses')->insert([
            PublishingHouse::NAME => 'Росмэн',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);
    }
}
