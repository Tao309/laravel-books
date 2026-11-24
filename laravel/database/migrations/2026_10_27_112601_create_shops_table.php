<?php

use App\Models\Shop;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Shop::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(Shop::PARAM_TYPE, 20);
            $table->string(Shop::PARAM_DOMAIN, 50);
            $table->string(Shop::PARAM_URL, 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Shop::TABLE_NAME);
    }
};
