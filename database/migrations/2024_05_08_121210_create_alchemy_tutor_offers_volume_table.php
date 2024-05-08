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
        Schema::create('alchemy_tutor_offers_volume', function (Blueprint $table) {
            $table->id();
            $table->integer('tutor_id');
            $table->integer('offers');
            $table->text('job_ids');
            $table->integer('step_1')->default(0);
            $table->integer('step_2')->default(0);
            $table->integer('step_3')->default(0);
            $table->integer('hidden')->default(0);
            $table->string('date_lastudate', 100);
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_tutor_offers_volume');
    }
};
