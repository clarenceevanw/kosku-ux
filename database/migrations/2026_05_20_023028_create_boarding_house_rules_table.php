<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: boarding_house_rules
     *
     * Many-to-many pivot linking boarding_houses → rules (master table).
     * Replaces the old free-text category/rule_text approach.
     *
     * One boarding house can adopt many standard rules.
     * The same rule can apply to many boarding houses.
     */
    public function up(): void
    {
        Schema::create('boarding_house_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('boarding_house_id')
                  ->constrained('boarding_houses')
                  ->cascadeOnDelete();

            $table->foreignUuid('rule_id')
                  ->constrained('rules')
                  ->cascadeOnDelete();

            $table->timestamps();

            // Prevent the same rule from being attached twice
            $table->unique(['boarding_house_id', 'rule_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boarding_house_rules');
    }
};
