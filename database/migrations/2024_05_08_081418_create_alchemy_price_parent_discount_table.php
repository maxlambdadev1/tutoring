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
        Schema::create('alchemy_price_parent_discount', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->float('discount_amount');
            $table->string('discount_type', 100);
            $table->float('online_amount');
            $table->string('online_type', 100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_price_parent_discount');
    }
};
