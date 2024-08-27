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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->foreignUuid('user_id')->references('uuid')->on('users');
            $table->foreignUuid('driver_id')->nullable()->references('uuid')->on('users');
            $table->foreignUuid('admin_id')->nullable()->references('uuid')->on('users');
            $table->string('longitude');
            $table->string('latitude');
            $table->float('distance');
            $table->enum('type', ['pengambilan sampah', 'pengantaran hadiah']);
            $table->enum('status', ['waiting', 'on the way', 'picked up', 'delivered', 'canceled']);
            $table->float('weight')->nullable();
            $table->text('description')->nullable();
            $table->datetime('confirmed_time')->nullable();
            $table->time('estimated_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
