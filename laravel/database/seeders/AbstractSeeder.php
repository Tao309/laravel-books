<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class AbstractSeeder extends Seeder
{
    protected function updateLastId(string $tableName, $paramId = 'id'): void
    {

        $pdoDriverName = DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);

        $tableName = DB::getTablePrefix().$tableName;

        if ($pdoDriverName === 'pgsql') {
            DB::update("SELECT setval(pg_get_serial_sequence('".$tableName."', 'id'), COALESCE(MAX(id), 0) + 1, false) FROM ".$tableName.';');

            return;
        }

        $lastId = $this->getLastId($tableName, $paramId);

        DB::update('ALTER TABLE '.$tableName.' AUTO_INCREMENT = '.($lastId + 1).';');
    }

    protected function getLastId(string $tableName, $paramId = 'id')
    {
        return DB::table($tableName)->max($paramId);
    }
}
