<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: boarding_house_rules
     * Depends on: boarding_houses
     *
     * 1-to-many: one boarding_house can have many categorised rules.
     * Rules are grouped by category for UI display (e.g. "Akses & Keamanan").
     */
    public function up(): void
    {
        Schema::create('boarding_house_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('boarding_house_id')
                  ->constrained('boarding_houses')
                  ->cascadeOnDelete();

            $table->string('category')->comment('e.g. Akses & Keamanan, Tamu, Kebersihan');
            $table->text('rule_text')->comment('Full detail of the rule');

            $table->timestamps();
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
