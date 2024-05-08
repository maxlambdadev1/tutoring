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
        Schema::create('alchemy_conversion_target', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id')->comment('job id from alchemy_jobs table');
            $table->integer('session_id')->comment('session id from alchemy_sessions table');
            $table->string('converted_by', 100)->nullable();
            $table->text('conversion_date');
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_conversion_target');
    }
};
