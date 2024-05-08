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
        Schema::create('alchemy_waiting_leads_offer', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id');
            $table->integer('tutor_id');
            $table->mediumText('date')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('reminder')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_waiting_leads_offer');
    }
};
