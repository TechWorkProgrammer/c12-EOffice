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
        Schema::create('questioner_users', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->foreignUuid('questioner_id')->references('uuid')->on('questioners');
            $table->foreignUuid('user_id')->references('uuid')->on('users');
            $table->integer('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questioner_users');
    }
};
