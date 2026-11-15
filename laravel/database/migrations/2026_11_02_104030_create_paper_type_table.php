<?php

use App\Models\PaperType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(PaperType::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(PaperType::PARAM_LABEL);
            $table->string(PaperType::PARAM_DESCRIPTION)->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paper_type');
    }
};
