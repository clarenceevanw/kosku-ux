<?php

use App\Enum\PriorityLevel;
use App\Enum\TicketStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: maintenance_tickets
     * Depends on: users, rooms
     *
     * Tenant-submitted damage/maintenance reports with priority and lifecycle status.
     */
    public function up(): void
    {
        Schema::create('maintenance_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUuid('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');
            $table->string('photo_url')->nullable();

            $table->enum('priority', array_column(PriorityLevel::cases(), 'value'))->default(PriorityLevel::NORMAL->value);

            $table->enum('status', array_column(TicketStatus::cases(), 'value'))->default(TicketStatus::REPORTED->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_tickets');
    }
};
