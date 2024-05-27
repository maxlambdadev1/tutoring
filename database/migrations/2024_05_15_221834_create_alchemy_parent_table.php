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
        Schema::create('alchemy_parent', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('parent_pass')->nullable();
            $table->string('parent_first_name', 100);
            $table->string('parent_last_name', 100);
            $table->string('parent_email', 100);
            $table->text('parent_address');
            $table->text('parent_suburb');
            $table->string('parent_postcode', 100);
            $table->string('parent_state', 100);
            $table->text('parent_lat')->nullable();
            $table->text('parent_lon')->nullable();
            $table->string('parent_phone', 100);
            $table->string('stripe_customer_id')->nullable();
            $table->string('referral_code', 10)->nullable();
            $table->integer('referred_id')->nullable();
            $table->integer('first_lesson_confirmed_id')->nullable();
            $table->string('first_lesson_confirmed_date')->nullable();
            $table->integer('first_lesson_credited_id')->nullable();  
            $table->string('heard_from', 100)->nullable();
            $table->string('voucher_number', 100)->nullable();
            $table->integer('mailchimp_status')->default(0);
            $table->integer('mailchimp_inactive_status')->default(0);
            $table->integer('5_sessions_podium_review')->default(0);
            $table->integer('subscribe')->default(0);
            $table->integer('subsequence_email_id')->default(0);
            $table->string('subsequence_email_last_updated', 50)->nullable();
            $table->tinyInteger('approve_child_safety')->default(0);
            $table->integer('thirdparty_org_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_parent');
    }
};
