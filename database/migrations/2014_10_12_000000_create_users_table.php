<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->string('phone_number', 15)->unique();
            $table->string('password');
            $table->text('address');
            $table->string('email')->nullable();
            $table->string('name');
            $table->date('birthdate');
            $table->enum('role', ['user', 'driver', 'admin'])->default('user');
            $table->boolean('is_verified')->default(false);
            $table->integer('point')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
