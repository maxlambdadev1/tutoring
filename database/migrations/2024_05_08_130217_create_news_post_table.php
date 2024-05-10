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
        Schema::create('news_post', function (Blueprint $table) {
            $table->id();       
            $table->integer('user_id');
            $table->longText('content')->nullable();
            $table->text('file')->nullable();
            $table->text('tagged_tutor')->nullable();
            $table->tinyInteger('allow')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->integer('pin')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_post');
    }
};
