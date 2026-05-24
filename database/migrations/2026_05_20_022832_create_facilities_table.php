<?php

use App\Enum\FacilityType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: facilities
     *
     * Standalone master/lookup table.
     * type: 'bersama' = shared area facilities (lobby, parking, laundry)
     *       'ruang'   = in-room facilities (AC, bed, private bathroom)
     * icon: Material Symbols Outlined ligature name.
     */
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->enum('type', array_column(FacilityType::cases(), 'value'))
                  ->default(FacilityType::BERSAMA->value)
                  ->comment('bersama = shared area | ruang = in-room');
            $table->string('icon')->nullable()->comment('Material Symbols Outlined ligature name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
