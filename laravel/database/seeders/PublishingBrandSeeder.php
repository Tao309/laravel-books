<?php

namespace Database\Seeders;

use App\Models\PublishingBrand;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublishingBrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('publishing_brands')->insert([
            PublishingBrand::NAME => 'Астрель СПб',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('publishing_brands')->insert([
            PublishingBrand::NAME => 'Молодежная литература. Фантастика и фэнтези',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('publishing_brands')->insert([
            PublishingBrand::NAME => 'Like Book',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('publishing_brands')->insert([
            PublishingBrand::NAME => 'fanzon',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('publishing_brands')->insert([
            PublishingBrand::NAME => 'Inspiria',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('publishing_brands')->insert([
            PublishingBrand::NAME => 'Вилли-Винки',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);
    }
}
