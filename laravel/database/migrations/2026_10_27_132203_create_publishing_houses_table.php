<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publishing_houses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publishing_houses');
    }
};
