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
        Schema::create('alchemy_sessions_reschedule', function (Blueprint $table) {
            $table->id();
            $table->integer('session_id');
            $table->string('old_date', 50);
            $table->string('old_time',50);
            $table->string('new_date', 100);
            $table->string('new_time', 100);
            $table->integer('hidden')->default(0);
            $table->string('date', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_sessions_reschedule');
    }
};
