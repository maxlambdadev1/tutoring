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
        Schema::create('alchemy_jobs_match_test', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->integer('distance');
            $table->integer('no_av')->default(0);
            $table->integer('tutors_found')->default(0);
            $table->text('tutor_ids')->nullable();
            $table->integer('reminder1')->default(0);
            $table->integer('reminder2')->default(0);
            $table->integer('reminder3')->default(0);
            $table->string('last_updated', 100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_jobs_match_test');
    }
};
