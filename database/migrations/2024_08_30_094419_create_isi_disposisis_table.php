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
        Schema::create('isi_disposisis', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->foreignUuid('disposisi_id')->references('uuid')->on('disposisis');
            $table->foreignUuid('isi_disposisi_id')->references('uuid')->on('m_isi_disposisis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('isi_disposisis');
    }
};
