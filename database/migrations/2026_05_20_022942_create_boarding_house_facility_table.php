<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: boarding_house_facility  (pivot / many-to-many)
     * Depends on: boarding_houses, facilities
     *
     * Stores general/common facilities available at a boarding house level.
     */
    public function up(): void
    {
        Schema::create('boarding_house_facility', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('boarding_house_id')
                ->constrained('boarding_houses')
                ->cascadeOnDelete();

            $table->foreignUuid('facility_id')
                ->constrained('facilities')
                ->cascadeOnDelete();

            $table->unique(['boarding_house_id', 'facility_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boarding_house_facility');
    }
};
