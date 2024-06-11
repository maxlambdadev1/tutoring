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
        Schema::create('alchemy_tutor_application_reference_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('application_id');
            $table->string('author', 100);
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_tutor_application_reference_history');
    }
};
