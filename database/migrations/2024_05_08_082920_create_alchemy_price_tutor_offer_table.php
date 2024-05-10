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
        Schema::create('alchemy_price_tutor_offer', function (Blueprint $table) {
            $table->id();
            $table->integer('tutor_id');
            $table->integer('parent_id');
            $table->integer('child_id');
            $table->float('offer_amount');
            $table->float('online_offer_amount');
            $table->string('offer_type', 100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_price_tutor_offer');
    }
};
