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
        Schema::create('alchemy_cancellation_fee', function (Blueprint $table) {
            $table->id();
            $table->integer('tutor_id');
            $table->integer('parent_id');
            $table->integer('child_id');
            $table->integer('session_id')->comment('session id from alchemy_sessions table');
            $table->string('session_date')->comment('session date');
            $table->longText('reason')->comment('reason');
            $table->string('bill_id')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->string('stripe_charge_time')->nullable();
            $table->string('stripe_transfer_id')->nullable();
            $table->integer('status')->default(0);
            $table->string('date_submitted');
            $table->string('date_last_updated')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_cancellation_fee');
    }
};
