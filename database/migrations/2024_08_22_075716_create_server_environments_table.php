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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->integer('prize_point')->default(1000);
            $table->integer('prize_expiration')->default(7);
            $table->integer('distance_maximum')->default(10);
            $table->integer('point_conversion_rate')->default(1);
            $table->integer('auto_cancel_minutes')->default(10);
            $table->text('admin_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_environments');
    }
};
