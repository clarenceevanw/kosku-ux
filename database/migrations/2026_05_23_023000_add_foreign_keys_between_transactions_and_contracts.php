<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->cascadeOnDelete();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('contract_id')
                ->references('id')
                ->on('contracts')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
        });
    }
};
