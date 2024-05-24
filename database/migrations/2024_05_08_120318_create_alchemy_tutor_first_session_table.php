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
        Schema::create('alchemy_tutor_first_session', function (Blueprint $table) {
            $table->id();
            $table->integer('tutor_id');
            $table->integer('status');
            $table->string('followup', 100)->nullable();
            $table->string('call_date', 100)->nullable();
            $table->integer('email_sent')->default(0);
            $table->integer('followup_sent')->default(0);
            $table->string('date_created', 100);
            $table->string('date_last_update', 100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_tutor_first_session');
    }
};
