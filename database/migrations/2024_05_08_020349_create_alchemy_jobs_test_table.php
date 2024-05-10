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
        Schema::create('alchemy_jobs_test', function (Blueprint $table) {
            $table->id('job_id');
            $table->string('job_type', 100)->default('regular');
            $table->unsignedBigInteger('replacement_id')->nullable();
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('child_id');
            $table->string('date', 100); 
            $table->string('time', 100)->nullable(); 
            $table->string('start_date', 100)->default('ASAP');
            $table->text('subject');
            $table->text('location');
            $table->string('prefered_gender', 10)->nullable();
            $table->longText('job_notes'); 
            $table->unsignedInteger('job_status');
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('accepted_by')->nullable();
            $table->string('accepted_on', 100)->nullable();
            $table->unsignedBigInteger('session_id')->nullable();
            $table->unsignedBigInteger('hidden')->default(0);
            $table->text('source')->nullable();
            $table->text('converted_by')->nullable();
            $table->unsignedBigInteger('3days_reminder')->default(0);
            $table->unsignedBigInteger('6days_reminder')->default(0);
            $table->unsignedBigInteger('9days_reminder')->default(0);
            $table->unsignedBigInteger('welcome_call')->default(0);
            $table->string('create_time', 100)->default('0');
            $table->text('last_updated'); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_jobs_test');
    }
};
