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
        Schema::create('alchemy_goal', function (Blueprint $table) {
            $table->id();
            $table->string('year', 50)->nullable();
            $table->string('quarter',50)->nullable();
            $table->string('month', 50)->nullable();
            $table->string('goal_start', 50)->nullable();
            $table->string('goal_current', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_goal');
    }
};
