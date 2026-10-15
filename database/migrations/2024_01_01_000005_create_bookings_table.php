<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('from_location_id')->nullable();
            $table->string('destination');
            $table->text('purpose');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->integer('passenger_count')->default(1);
            $table->enum('status', [
                'pending',
                'approved_l1',
                'approved',
                'rejected',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->integer('odometer_start')->nullable();
            $table->integer('odometer_end')->nullable();
            $table->decimal('fuel_used', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('driver_id')->references('id')->on('drivers')->nullOnDelete();
            $table->foreign('from_location_id')->references('id')->on('locations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
