<?php

use App\Models\SourceProductType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(SourceProductType::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(SourceProductType::PARAM_CODE, 50)->unique();
            $table->string(SourceProductType::PARAM_NAME, 50)->unique();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(SourceProductType::TABLE_NAME);
    }
};
