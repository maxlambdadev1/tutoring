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
        Schema::create('alchemy_holiday_replacement_history', function (Blueprint $table) {
            $table->id();
            $table->integer('holiday_id');
            $table->string('author', 100);
            $table->text('comment');
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
        Schema::dropIfExists('alchemy_holiday_replacement_history');
    }
};
