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
        Schema::create('alchemy_pipeline_dds', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('email')->nullable();
            $table->text('status')->nullable();
            $table->date('date_added')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_pipeline_dds');
    }
};
