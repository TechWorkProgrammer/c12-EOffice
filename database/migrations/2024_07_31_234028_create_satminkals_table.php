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
        Schema::create('satminkals', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->string('name');
            $table->string('kode_kotama');
            $table->string('kode_satminkal');
            $table->foreign('kode_kotama')->references('kode')->on('kotamas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satminkals');
    }
};
