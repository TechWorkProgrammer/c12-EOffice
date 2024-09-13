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
        Schema::create('log_disposisis', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->foreignUuid('pengirim')->references('uuid')->on('m_users');
            $table->foreignUuid('penerima')->references('uuid')->on('m_users');
            $table->foreignUuid('disposisi_id')->references('uuid')->on('disposisis');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('pelaksanaan_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_disposisis');
    }
};
