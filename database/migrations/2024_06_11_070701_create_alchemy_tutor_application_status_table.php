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
        Schema::create('alchemy_tutor_application_status', function (Blueprint $table) {
            $table->id('application_id');
            $table->integer('application_status');
            $table->string('date_follow_up')->nullable();
            $table->integer('followup_counter')->default(0);
            $table->string('date_last_update')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_tutor_application_status');
    }
};
