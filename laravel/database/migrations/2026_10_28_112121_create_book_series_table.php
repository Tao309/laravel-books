<?php

use App\Models\BookSeries;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(BookSeries::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(BookSeries::PARAM_NAME, 255);
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(BookSeries::TABLE_NAME);
    }
};
