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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('receptionist_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['info', 'warning', 'error'])->default('info');

            $table->foreign('user_id')->references('id')->on('guests')->onDelete('cascade');
            $table->foreign('receptionist_id')->references('id')->on('receptionists')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
