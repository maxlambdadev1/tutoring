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
        Schema::create('news_notification', function (Blueprint $table) {
            $table->id();
            $table->integer('send_id');
            $table->integer('receive_id');
            $table->integer('notifiable_id');
            $table->string('type', 50);
            $table->tinyInteger('read')->default(0);            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_notification');
    }
};
