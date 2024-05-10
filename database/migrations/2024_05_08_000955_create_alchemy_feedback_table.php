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
        Schema::create('alchemy_feedback', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('user id from alchemy_users table');
            $table->integer('user_type');
            $table->longText('summary');
            $table->longText('feedback');
            $table->string('type', 100);
            $table->string('mood', 100);
            $table->string('referer', 100);
            $table->string('date', 100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_feedback');
    }
};
