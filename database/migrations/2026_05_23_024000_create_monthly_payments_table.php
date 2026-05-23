<?php

use App\Enum\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: monthly_payments
     * Depends on: contracts
     * 
     * Tracks monthly recurring payments for each contract.
     * Generated automatically when contract is created.
     */
    public function up(): void
    {
        Schema::create('monthly_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('contract_id')
                ->constrained('contracts')
                ->cascadeOnDelete();

            $table->integer('billing_month')->comment('Month number: 1 = first month, 2 = second, etc');
            $table->date('due_date');
            $table->integer('amount');

            $table->enum('payment_status', array_column(PaymentStatus::cases(), 'value'))
                ->default(PaymentStatus::PENDING->value);

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
        Schema::dropIfExists('monthly_payments');
    }
};
