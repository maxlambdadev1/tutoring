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
        Schema::create('alchemy_online_automation_limit', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('job_id');
            $table->text('tutor_ids')->nullable();
            $table->unsignedInteger('update_avail_status')->default(0);
            $table->unsignedInteger('update_avail_action_handled')->default(0);
            $table->string('last_updated', 255)->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_online_automation_limit');
    }
};
