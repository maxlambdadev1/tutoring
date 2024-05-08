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
            $table->text('an_text');
            $table->integer('an_posted_by')->comment('user id from alchemy_user table');
            $table->string('an_date');
            $table->string('an_time');
            $table->smallInteger('who')->default(1);
            $table->tinyInteger('flag')->default(0);
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
