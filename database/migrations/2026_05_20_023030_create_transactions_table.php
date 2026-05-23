<?php

use App\Enum\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: transactions
     * Depends on: users, rooms
     *
     * Escrow-based payment flow: pending → paid_to_escrow → released_to_owner
     *                                                      ↘ cancelled
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUuid('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->date('start_date');
            $table->date('end_date');

            $table->integer('total_amount');

            $table->enum('payment_status', array_column(PaymentStatus::cases(), 'value'))->default(PaymentStatus::PENDING->value);

            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
