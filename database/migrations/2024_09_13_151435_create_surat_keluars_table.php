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
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->string('nomor_surat');
            $table->foreignUuid('klasifikasi_surat_id')->references('uuid')->on('m_klasifikasi_surats');
            $table->foreignUuid('pengirim')->references('uuid')->on('m_users');
            $table->string('tipe');
            $table->string('perihal');
            $table->string('file_surat');
            $table->string('tujuan');
            $table->foreignUuid('created_by')->references('uuid')->on('m_users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
