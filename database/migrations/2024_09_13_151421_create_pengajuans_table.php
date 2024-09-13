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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->foreignUuid('draft_id')->references('uuid')->on('drafts');
            $table->foreignUuid('penerima_id')->references('uuid')->on('m_users');
            $table->enum('status', ['Diterima', 'Ditolak'])->nullable();
            $table->text('catatan')->nullable();
            $table->string('tanda_tangan')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignUuid('pengajuan_asal')->nullable()->references('uuid')->on('pengajuans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
