<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refactor boarding_houses: replace flat city/province strings with a
     * normalized district_id FK that chains up to cities → provinces via JOIN.
     *
     * Run AFTER: create_districts_table (2026_06_07_000003)
     */
    public function up(): void
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            // Drop old denormalized columns
            $table->dropColumn(['city', 'province']);

            // Add normalized FK — nullable during migration to avoid issues
            // when existing rows already exist; set NOT NULL after seeding.
            $table->char('district_id', 7)->nullable()->after('address');

            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropColumn('district_id');

            // Restore original columns
            $table->string('city')->after('address');
            $table->string('province')->after('city');
        });
    }
};
