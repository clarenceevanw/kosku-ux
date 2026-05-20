<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: rooms
     * Depends on: boarding_houses
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('boarding_house_id')
                  ->constrained('boarding_houses')
                  ->cascadeOnDelete();

            $table->string('type_name')->comment('e.g. Kamar Standar, Kamar VIP');
            $table->integer('price_per_month');
            $table->integer('stock');
            $table->string('size')->nullable()->comment('e.g. 3x4 m');
            $table->string('image_url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
