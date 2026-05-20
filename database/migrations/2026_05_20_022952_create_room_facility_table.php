<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: room_facility  (pivot / many-to-many)
     * Depends on: rooms, facilities
     *
     * Stores specific in-room facilities (e.g. AC, private bathroom).
     */
    public function up(): void
    {
        Schema::create('room_facility', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->foreignUuid('facility_id')
                ->constrained('facilities')
                ->cascadeOnDelete();

            $table->unique(['room_id', 'facility_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_facility');
    }
};
