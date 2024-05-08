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
        Schema::create('alchemy_holiday_reach_parent', function (Blueprint $table) {
            $table->id();
            $table->integer('holiday_id');
            $table->tinyInteger('sms_first')->default(0);
            $table->tinyInteger('email_first')->default(0);
            $table->tinyInteger('sms_second')->default(0);
            $table->tinyInteger('email_second')->default(0);
            $table->mediumText('link')->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_holiday_reach_parent');
    }
};
