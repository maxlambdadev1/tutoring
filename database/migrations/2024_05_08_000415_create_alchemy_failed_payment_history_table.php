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
        Schema::create('alchemy_failed_payment_history', function (Blueprint $table) {
            $table->id();
            $table->integer('session_id')->comment('session id from alchemy_sessions table');
            $table->string('author', 50);
            $table->mediumText('comment')->nullable();
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
        Schema::dropIfExists('alchemy_failed_payment_history');
    }
};
