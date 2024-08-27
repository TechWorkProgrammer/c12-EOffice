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
        Schema::create('history_points', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->foreignUuid('user_id')->references('uuid')->on('users');
            $table->foreignUuid('delivery_id')->nullable()->references('uuid')->on('deliveries');
            $table->foreignUuid('prizes_id')->nullable()->references('uuid')->on('prizes');
            $table->text('description');
            $table->integer('point');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_points');
    }
};
