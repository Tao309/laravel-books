<?php

use App\Models\SourceProduct;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(SourceProduct::TABLE_NAME, function (Blueprint $table) {
            $table->integer(SourceProduct::PARAM_ON_SALE_COUNT)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table(SourceProduct::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn(SourceProduct::PARAM_ON_SALE_COUNT);
        });
    }
};
