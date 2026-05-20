<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: reviews
     * Depends on: contracts, users, boarding_houses
     *
     * "Verified Review" pattern:
     *   - 1-to-1 with contracts (unique constraint on contract_id)
     *   - tenant_id & boarding_house_id stored for fast query / avg-rating aggregation
     *     without extra JOINs through contracts → transactions.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // 1-to-1: exactly one review per contract (verified purchase)
            $table->foreignUuid('contract_id')
                  ->unique()
                  ->constrained('contracts')
                  ->cascadeOnDelete()
                  ->comment('1 contract = 1 verified review maximum');

            // Denormalised FKs for performance — avoids deep joins on aggregations
            $table->foreignUuid('tenant_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignUuid('boarding_house_id')
                  ->constrained('boarding_houses')
                  ->cascadeOnDelete()
                  ->comment('Stored for fast avg-rating calculation per boarding house');

            // Rating 1–5, enforced at application layer via validation
            $table->tinyInteger('rating')->unsigned()->comment('Scale 1 to 5');

            $table->text('comment')->nullable();

            $table->timestamps();

            // Composite index to speed up "all reviews for a boarding house" queries
            $table->index(['boarding_house_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
