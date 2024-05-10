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
        Schema::create('alchemy_second_session', function (Blueprint $table) {
            $table->id('first_session_id');
            $table->integer('tutor_id');
            $table->integer('child_id');
            $table->integer('parent_id');
            $table->text('unique_link');
            $table->integer('second_session');
            $table->integer('second_session_id');
            $table->text('payment_details');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_second_session');
    }
};
