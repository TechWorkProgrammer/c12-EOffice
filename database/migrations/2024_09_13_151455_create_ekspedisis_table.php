<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ekspedisis', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->string('name');
            $table->foreignUuid('surat_keluar_id')->references('uuid')->on('surat_keluars');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekspedisis');
    }
};
