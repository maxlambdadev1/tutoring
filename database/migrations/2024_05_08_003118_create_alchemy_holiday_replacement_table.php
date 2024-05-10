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
        Schema::create('alchemy_holiday_replacement', function (Blueprint $table) {
            $table->id();
            $table->integer('child_id');
            $table->integer('year');
            $table->integer('replacement_id');
            $table->string('date_created', 100);
            $table->string('date_last_modified',100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_holiday_replacement');
    }
};
