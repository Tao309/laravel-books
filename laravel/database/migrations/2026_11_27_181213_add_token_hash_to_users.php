<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(User::TABLE_NAME, function (Blueprint $table) {
            $table->char(User::PARAM_TOKEN_HASH, 64)->nullable();
        });

        DB::table(User::TABLE_NAME)
            ->whereNotNull(User::PARAM_REMEMBER_TOKEN)
            ->select([User::PARAM_ID, User::PARAM_REMEMBER_TOKEN])
            ->chunkById(500, function ($users) {
                foreach ($users as $user) {
                    DB::table(User::TABLE_NAME)
                        ->where(User::PARAM_ID, $user->id)
                        ->update([
                            User::PARAM_REMEMBER_TOKEN => null,
                            User::PARAM_TOKEN_HASH => hash('sha256', $user->remember_token),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table(User::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn(User::PARAM_TOKEN_HASH);
        });
    }
};
