<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messenger_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->foreignId('api_token_id')
                ->nullable()
                ->constrained('api_tokens')
                ->nullOnDelete();

            // telegram, max, vk
            $table->string('platform', 32);

            // Внешний ID пользователя в мессенджере
            $table->string('external_id');

            $table->string('username')->nullable();
            $table->string('first_name')->nullable();

            $table->timestamp('linked_at')->nullable();

            $table->timestamps();

            // Один внешний аккаунт нельзя привязать к разным пользователям
            $table->unique(['platform', 'external_id']);

            // Один пользователь может иметь только один аккаунт каждой платформы
            $table->unique(['user_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messenger_accounts');
    }
};
