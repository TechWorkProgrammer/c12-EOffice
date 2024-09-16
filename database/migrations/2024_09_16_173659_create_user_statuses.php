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
        Schema::create('user_statuses', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->foreignUuid('user_id')->references('uuid')->on('m_users')->onDelete('cascade');
            $table->foreignUuid('surat_masuk_id')->references('uuid')->on('surat_masuks')->onDelete('cascade');
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
        Schema::dropIfExists('user_statuses');
    }
};
