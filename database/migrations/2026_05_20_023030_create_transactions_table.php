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
     * Depends on: users, rooms, contracts
     *
     * Escrow-based payment flow: pending → paid_to_escrow → released_to_owner
     *                                                      ↘ cancelled
     * 
     * Monthly billing system:
     * - Each contract generates multiple transactions (one per month)
     * - billing_month tracks which month (1 = first month, 2 = second, etc)
     * - due_date is when payment is due
     * - paid_at tracks when payment was completed
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

            $table->uuid('contract_id')->nullable();

            $table->date('start_date');
            $table->date('end_date');
            
            $table->integer('billing_month')->nullable();
            $table->date('due_date')->nullable();

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
