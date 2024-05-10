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
        Schema::create('alchemy_booking_target', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id')->comment('job id from alchemy_jobs table');
            $table->string('source')->default('online');
            $table->string('booking_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_booking_target');
    }
};
