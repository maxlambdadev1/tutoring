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
        Schema::create('alchemy_tutor_special_referral_email', function (Blueprint $table) {
            $table->id();
            $table->integer('tutor_id');
            $table->integer('reminder1')->default(0);
            $table->integer('reminder2')->default(0);
            $table->integer('reminder3')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_tutor_special_referral_email');
    }
};
