<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('binding_types', function (Blueprint $table) {
            $table->id();
            $table->string('label', 50);
            $table->timestampsTz();
            $table->unique(['label']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binding_types');
    }
};
