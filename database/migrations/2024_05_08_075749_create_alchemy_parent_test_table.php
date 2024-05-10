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
        Schema::create('alchemy_parent_test', function (Blueprint $table) {
            $table->id('parent_id');
            $table->integer('user_id');
            $table->string('parent_pass')->nullable();
            $table->string('parent_first_name', 100);
            $table->string('parent_last_name', 100)->nullable();
            $table->string('parent_email', 100);
            $table->text('parent_address');
            $table->text('suburb');
            $table->string('parent_postcode', 100);
            $table->string('parent_state')->nullable();
            $table->string('parent_lat')->nullable();
            $table->string('parent_lon')->nullable();
            $table->string('parent_phone', 100);
            $table->string('stripe_customer_id')->nullable();
            $table->string('referrer')->nullable();
            $table->string('heard_from', 100)->nullable();
            $table->integer('mailchimp_status');
            $table->integer('mailchimp_inactive_status');
            $table->integer('5_sessions_podium_review');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_parent_test');
    }
};
