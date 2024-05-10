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
        Schema::create('alchemy_replacement_tutor', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tutor_id');
            $table->unsignedInteger('parent_id');
            $table->unsignedInteger('child_id');
            $table->unsignedInteger('last_session');
            $table->unsignedInteger('job_id')->nullable();
            $table->unsignedInteger('replacement_tutor_id')->nullable();
            $table->unsignedInteger('replacement_status');
            $table->string('tutor_last_session')->nullable();
            $table->text('tutor_notes')->nullable();
            $table->text('tutor_link')->nullable();
            $table->unsignedInteger('parent_day')->nullable();
            $table->string('parent_time', 50)->nullable();
            $table->text('parent_notes')->nullable();
            $table->text('parent_link')->nullable();
            $table->string('date_added', 50);
            $table->string('last_modified', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_replacement_tutor');
    }
};
