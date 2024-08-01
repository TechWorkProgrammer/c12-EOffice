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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 15)->unique();
            $table->string('password');
            $table->string('address', 255);
            $table->string('email', 255)->nullable();
            $table->string('name', 255);
            $table->date('birthdate');
            $table->string('role', 50)->default('user');
            $table->boolean('is_verified')->default(false);
            $table->integer('point')->default(0);
            $table->boolean('questioner_submitted')->default(false);
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
