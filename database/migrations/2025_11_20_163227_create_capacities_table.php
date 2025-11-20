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
        Schema::create('capacities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_id');
            $table->dateTime('venue_date');
            $table->integer('full_capacity');
            $table->integer('min_capacity');
            $table->integer('available_capacity');
            $table->integer('total_paid');
            $table->integer('total_reserved');
            $table->integer('status');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('venue_id')->references('id')->on('venues');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capacities');
    }
};
