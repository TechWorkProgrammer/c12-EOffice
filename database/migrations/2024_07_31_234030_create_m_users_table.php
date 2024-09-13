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
        Schema::create('m_users', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['Tata Usaha', 'Pejabat', 'Pelaksana', 'Eksternal', 'Administrator']);
            $table->foreignUuid('pejabat_id')->nullable()->references('uuid')->on('m_pejabats');
            $table->foreignUuid('satminkal_id')->nullable()->references('uuid')->on('satminkals');
            $table->string('password');
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
