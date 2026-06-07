<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: districts (Kecamatan)
     * BPS standard: 7-char code (e.g. "3578010" = Kec. Wonocolo)
     * Depends on: cities
     */
    public function up(): void
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->char('id', 7)->primary();
            $table->char('city_id', 4);
            $table->string('name');

            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
