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
        Schema::create('session_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('kind')->nullable();
            $table->string('session_price')->nullable();
            $table->string('tutor_price')->nullable();
            $table->string('increase_rate')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_types');
    }
};
