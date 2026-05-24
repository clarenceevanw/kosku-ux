<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: rules
     *
     * Master lookup table for standardised boarding-house rules.
     * Boarding houses link to these via the boarding_house_rules pivot.
     *
     * category — grouping label, e.g. "Tamu & Kunjungan", "Keamanan & Akses"
     * name     — concise rule display text, e.g. "Tamu dilarang menginap"
     * icon     — Material Symbols Outlined ligature name
     */
    public function up(): void
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('category')->comment('e.g. Keamanan & Akses, Tamu & Kunjungan, Kebersihan');
            $table->string('name')->comment('Short display text of the rule');
            $table->string('icon')->nullable()->comment('Material Symbols Outlined ligature name');
            $table->timestamps();

            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
