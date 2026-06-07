<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: landmarks
     * Stores notable points of interest used for distance-based kos search.
     * Depends on: districts
     */
    public function up(): void
    {
        Schema::create('landmarks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->char('district_id', 7);
            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->cascadeOnDelete();

            $table->string('name');

            $table->enum('type', ['campus', 'station', 'mall'])
                ->comment('Category of the landmark for proximity filtering');

            // Geographic coordinates — decimal(10,7) gives ~1 cm precision
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landmarks');
    }
};
