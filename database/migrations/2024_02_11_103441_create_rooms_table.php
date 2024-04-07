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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number');
            $table->unsignedBigInteger('room_type_id');
            $table->foreign('room_type_id')->references('id')->on('room_types');
            $table->unsignedBigInteger('food_id');
            $table->foreign('food_id')->references('id')->on('room__food');
            $table->unsignedBigInteger('room_status_id');
            $table->foreign('room_status_id')->references('id')->on('room__statuses');
            $table->decimal('price', 10, 2);
            $table->integer('bed');
            $table->integer('bathroom');
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->text('description')->nullable();
            $table->integer('ability');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
