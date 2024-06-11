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
        Schema::create('alchemy_tutor_application_queue', function (Blueprint $table) {
            $table->id('app_id');
            $table->string('tutor_email', 100);
            $table->string('tutor_first_name', 100);
            $table->string('email');
            $table->integer('glassdoor_review');
            $table->string('date_lastupdated', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_tutor_application_queue');
    }
};
