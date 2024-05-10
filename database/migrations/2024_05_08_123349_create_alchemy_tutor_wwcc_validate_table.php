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
        Schema::create('alchemy_tutor_wwcc_validate', function (Blueprint $table) {
            $table->id();
            $table->integer('tutor_id');
            $table->string('timestamp');
            $table->integer('4w_reminder')->default(0);
            $table->integer('5w_reminder')->default(0);
            $table->integer('6w_reminder')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_tutor_wwcc_validate');
    }
};
