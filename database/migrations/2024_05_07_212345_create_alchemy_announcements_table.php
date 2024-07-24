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
        Schema::create('alchemy_announcements', function (Blueprint $table) {
            $table->id('an_id');
            $table->text('an_text')->nullable();
            $table->integer('an_posted_by')->comment('user id from alchemy_user table')->nullable();
            $table->string('an_date')->nullable();
            $table->string('an_time')->nullable();
            $table->smallInteger('who')->default(1);
            $table->tinyInteger('flag')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_announcements');
    }
};
