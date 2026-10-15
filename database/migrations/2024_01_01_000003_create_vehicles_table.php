<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->year('year')->nullable();
            $table->string('plate_number')->unique();
            $table->enum('type', ['angkutan_orang', 'angkutan_barang'])->default('angkutan_orang');
            $table->enum('ownership', ['milik', 'sewa'])->default('milik');
            $table->string('rental_company')->nullable();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'inactive'])->default('available');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->integer('fuel_consumption')->nullable();
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable();
            $table->integer('service_interval_km')->nullable()->default(5000);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
