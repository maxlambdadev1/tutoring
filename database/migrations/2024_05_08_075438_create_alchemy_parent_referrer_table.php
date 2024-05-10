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
        Schema::create('alchemy_parent_referrer', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->string('referral_code', 30);
            $table->integer('first_lesson_confirmed_id');
            $table->integer('first_lesson_credited_id');  
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_parent_referrer');
    }
};
