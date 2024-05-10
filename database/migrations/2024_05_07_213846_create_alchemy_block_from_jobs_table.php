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
        Schema::create('alchemy_block_from_jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('tutor_id')->nullable()->comment('tutor id from alchemy_tutor table');
            $table->integer('not_continue_number')->nullable();
            $table->integer('type')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_block_from_jobs');
    }
};
