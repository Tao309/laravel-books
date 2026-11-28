<?php

use App\Enums\RoleCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Role::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(Role::PARAM_CODE)->unique();
            $table->string(Role::PARAM_TITLE);
            $table->timestampTz(Role::PARAM_DATE_CREATED);
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('role_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['user_id', 'role_id']);
        });

        DB::table(Role::TABLE_NAME)->insertOrIgnore([
            [
                'code' => RoleCode::ADMIN,
                'title' => 'Admin',
                'date_created' => now(),
            ],
            [
                'code' => RoleCode::USER,
                'title' => 'User',
                'date_created' => now(),
            ],
            [
                'code' => RoleCode::API,
                'title' => 'API',
                'date_created' => now(),
            ],
            [
                'code' => RoleCode::BOT,
                'title' => 'Bot',
                'date_created' => now(),
            ],
            [
                'code' => RoleCode::MODERATOR,
                'title' => 'Moderator',
                'date_created' => now(),
            ],
            [
                'code' => RoleCode::MANAGER,
                'title' => 'Manager',
                'date_created' => now(),
            ],
            [
                'code' => RoleCode::CONTENT_MANAGER,
                'title' => 'Content Manager',
                'date_created' => now(),
            ],
            [
                'code' => RoleCode::CUSTOMER,
                'title' => 'Customer',
                'date_created' => now(),
            ],
        ]);

        $adminRoleId = DB::table(Role::TABLE_NAME)
            ->where(Role::PARAM_CODE, RoleCode::ADMIN)
            ->value(Role::PARAM_ID);

        $userRoleId = DB::table(Role::TABLE_NAME)
            ->where(Role::PARAM_CODE, RoleCode::USER)
            ->value(Role::PARAM_ID);

        DB::table(User::TABLE_NAME)
            ->select(User::PARAM_ID, User::PARAM_NAME)
            ->orderBy(User::PARAM_ID)
            ->each(function ($user) use ($adminRoleId, $userRoleId) {
                $rolesData = [
                    [
                        'user_id' => $user->id,
                        'role_id' => $userRoleId,
                    ],
                ];

                if (in_array($user->name, [env('NAME_ADMIN'), env('NAME_USER_1')])) {
                    $rolesData[] = [
                        'user_id' => $user->id,
                        'role_id' => $adminRoleId,
                    ];
                }

                DB::table('role_user')->insertOrIgnore($rolesData);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists(Role::TABLE_NAME);
    }
};
