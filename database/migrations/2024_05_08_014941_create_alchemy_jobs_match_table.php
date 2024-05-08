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
        Schema::create('alchemy_jobs_match', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->integer('distance');
            $table->integer('no_av')->default(0);
            $table->integer('tutors_found')->default(0);
            $table->text('tutor_ids')->nullable();
            $table->mediumText('tutor_ids_full')->nullable();
            $table->integer('automation_step')->default(0);
            $table->integer('reminder1')->default(0);
            $table->integer('update_avail_status')->default(0);
            $table->integer('reminder2')->default(0);
            $table->integer('change_online_status')->default(0);
            $table->integer('reminder3')->default(0);
            $table->integer('reminder4')->default(0);
            $table->string('last_updated', 100);
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_jobs_match');
    }
};
