<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class UserSeeder extends AbstractSeeder
{
    public function run(): void
    {
        $insertingData = [];

        foreach ($this->getData() as $row) {
            if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                continue;
            }

            $insertingData[] = [
                User::PARAM_ID => $row[0],
                User::PARAM_NAME => $row[1],
                User::PARAM_EMAIL => $row[1].'@localhost.ru',
                User::PARAM_PASSWORD => Hash::make(Str::password(8)),
                User::PARAM_REMEMBER_TOKEN => $row[2],
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ];
        }

        if (empty($insertingData)) {
            throw new RuntimeException('Not found data for seeding');
        }

        DB::table(User::TABLE_NAME)->insert($insertingData);
    }

    private function getData(): array
    {
        return [
            [
                env('ID_ADMIN'),
                env('NAME_ADMIN'),
                env('TOKEN_ADMIN'),
            ],
            [
                env('ID_USER_1'),
                env('NAME_USER_1'),
                env('TOKEN_USER_1'),
            ],
            [
                env('ID_USER_2'),
                env('NAME_USER_2'),
                env('TOKEN_USER_2'),
            ],
            [
                env('ID_USER_3'),
                env('NAME_USER_3'),
                env('TOKEN_USER_3'),
            ],
        ];
    }
}
