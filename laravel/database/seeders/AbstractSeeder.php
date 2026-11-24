<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class AbstractSeeder extends Seeder
{
    protected function getFirstUser(): User
    {
        return User::firstOrFail();
    }

    protected function updateLastId(string $tableName, string $paramId = 'id'): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver !== 'pgsql') {
            return;
        }

        $lastId = DB::table($tableName)->max($paramId) ?? 0;

        $tableName = DB::getTablePrefix() . $tableName;

        DB::selectOne(
            'SELECT setval(pg_get_serial_sequence(?, ?), ?, false)',
            [$tableName, $paramId, $lastId + 1]
        );
    }
}
