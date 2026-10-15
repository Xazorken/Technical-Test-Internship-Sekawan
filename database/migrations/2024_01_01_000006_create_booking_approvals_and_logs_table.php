<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('approver_id');
            $table->integer('level');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('acted_at')->nullable();
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
            $table->foreign('approver_id')->references('id')->on('users');
        });

        Schema::create('vehicle_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->date('service_date');
            $table->string('service_type');
            $table->text('description')->nullable();
            $table->integer('odometer')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('workshop')->nullable();
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->cascadeOnDelete();
        });

        Schema::create('vehicle_fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->date('date');
            $table->decimal('amount_liter', 8, 2);
            $table->decimal('cost_per_liter', 10, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->integer('odometer')->nullable();
            $table->string('fuel_type')->default('Pertalite');
            $table->string('recorded_by')->nullable();
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->cascadeOnDelete();
            $table->foreign('booking_id')->references('id')->on('bookings')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_fuel_logs');
        Schema::dropIfExists('vehicle_services');
        Schema::dropIfExists('booking_approvals');
    }
};
