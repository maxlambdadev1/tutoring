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
        Schema::create('alchemy_recruiter', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('ABN', 111)->nullable();
            $table->string('bank_account_name', 111)->nullable();
            $table->string('bsb', 111)->nullable();
            $table->string('bank_account_number', 111)->nullable();
            $table->text('id_photo')->nullable();
            $table->text('signature')->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('pass', 111)->nullable();
            $table->string('stripe_user_id', 111)->nullable();
            $table->string('access_token', 111)->nullable();
            $table->string('stripe_publishable_key', 111)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('logged_status')->default(0);
            $table->string('state', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('suburb', 50)->nullable();
            $table->string('postcode',50)->nullable();
            $table->string('referral_key',50)->nullable();
            $table->string('last_updated',50)->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_recruiter');
    }
};
