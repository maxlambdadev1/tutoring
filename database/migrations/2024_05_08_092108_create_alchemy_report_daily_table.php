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
        Schema::create('alchemy_report_daily', function (Blueprint $table) {
            $table->id();
            $table->string('date', 50);
            $table->string('day', 50);
            $table->integer('bookings');
            $table->integer('conversions');
            $table->integer('tutor_conversions')->default(0);
            $table->integer('team_conversions')->default(0);
            $table->integer('total_confirmed_sessions');
            $table->integer('confirmed_first_sessions');
            $table->integer('total_confirmed_hours');
            $table->integer('leads_in_system');
            $table->string('date_last_updated', 50);
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_report_daily');
    }
};
