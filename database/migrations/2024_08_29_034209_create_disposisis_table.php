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
        Schema::create('disposisis', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->foreignUuid('surat_id')->references('uuid')->on('surat_masuks');
            $table->foreignUuid('disposisi_asal')->nullable()->references('uuid')->on('disposisis');
            $table->text('catatan')->nullable();
            $table->string('tanda_tangan');
            $table->foreignUuid('created_by')->references('uuid')->on('m_users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposis');
    }
};
