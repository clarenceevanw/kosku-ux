<?php

use App\Enum\ContractStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: contracts
     * Depends on: transactions
     *
     * 1-to-1 relationship with initial transaction.
     * After contract created, generates monthly billing transactions.
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('room_id')->constrained('rooms')->cascadeOnDelete();

            $table->string('contract_number')
                  ->unique()
                  ->comment('Human-readable ref, e.g. #KOS-2026-9912');

            $table->date('start_date');
            $table->date('end_date');

            $table->integer('monthly_fee');
            $table->integer('deposit_fee');

            // Nullable: set when each party signs digitally
            $table->timestamp('tenant_signature_date')->nullable()->comment('UTC timestamp of tenant e-signature');
            $table->timestamp('owner_signature_date')->nullable()->comment('UTC timestamp of owner e-signature');

            $table->string('pdf_url')->nullable()->comment('AWS S3 / GDrive link to signed PDF');

            $table->enum('status', array_column(ContractStatus::cases(), 'value'))->default(ContractStatus::ACTIVE->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
