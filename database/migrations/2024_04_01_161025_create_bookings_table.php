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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('numero_booking');
            $table->unsignedBigInteger('room_number');
            $table->foreign('room_number')->references('id')->on('rooms');
            $table->unsignedBigInteger('guest_id');
            $table->foreign('guest_id')->references('id')->on('guests');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->string('price');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
