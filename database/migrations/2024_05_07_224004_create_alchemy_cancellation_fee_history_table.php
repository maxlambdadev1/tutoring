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
        Schema::create('alchemy_cancellation_fee_history', function (Blueprint $table) {
            $table->id();
            $table->integer('cancellation_id')->comment('cancellation id from alchemy_cancellation_fee table');
            $table->string('author', 100);
            $table->text('comment');
            $table->string('date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_cancellation_fee_history');
    }
};
