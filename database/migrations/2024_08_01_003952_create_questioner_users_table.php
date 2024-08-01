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
            $table->id();
            $table->foreignId('questioner_id')->constrained('questioners');
            $table->foreignId('user_id')->constrained('users');
            $table->text('answer');
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
