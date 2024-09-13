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
        Schema::create('drafts', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->string('perihal');
            $table->string('file_surat');
            $table->enum('status', ['Proses', 'Ditolak', 'Diterima', 'Terkirim']);
            $table->foreignUuid('created_by')->references('uuid')->on('m_users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drafts');
    }
};
