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
        Schema::create('pojok_edukasi_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pojok_edukasi_id')->constrained('pojok_edukasis');
            $table->string('name', 255);
            $table->string('link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pojok_edukasi_contents');
    }
};
