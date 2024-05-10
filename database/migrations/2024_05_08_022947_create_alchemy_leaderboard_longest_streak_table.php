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
        Schema::create('alchemy_leaderboard_longest_streak', function (Blueprint $table) {
            $table->id();
            $table->integer('tutor_id');
            $table->string('tutor_name', 100);
            $table->text('tutor_image');
            $table->integer('week_number');
            $table->string('student_name', 100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_leaderboard_longest_streak');
    }
};
