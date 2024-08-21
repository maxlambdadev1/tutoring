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
        Schema::create('alchemy_sessions', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('type_id')->default(1);
            $table->integer('session_status');
            $table->unsignedBigInteger('tutor_id');
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('child_id');
            $table->string('session_date', 50)->nullable();
            $table->string('session_time', 50)->nullable();
            $table->text('session_subject');
            $table->boolean('session_is_first')->default(0);
            $table->text('session_first_question1')->nullable();
            $table->text('session_first_question2')->nullable();
            $table->text('session_first_question3')->nullable();
            $table->text('session_first_question4')->nullable();
            $table->string('session_length')->nullable();
            $table->text('session_reason')->nullable();
            $table->string('session_price');
            $table->decimal('session_tutor_price', 4, 2)->nullable();
            $table->string('session_third_price')->nullable();
            $table->decimal('session_penalty', 4, 2)->default(0.00);
            $table->string('session_charge_time', 50)->nullable();
            $table->string('session_transfer_time', 250)->nullable();
            $table->string('session_charge_id')->nullable();
            $table->string('session_transfer_id')->nullable();
            $table->string('session_invoice_id')->nullable();
            $table->string('session_bill_id')->nullable();
            $table->boolean('session_manual_marked_paid')->default(0);
            $table->unsignedBigInteger('session_next_session_id')->nullable();
            $table->string('session_next_session_tutor_date', 100)->nullable();
            $table->string('session_next_session_tutor_time', 100)->nullable();
            $table->unsignedBigInteger('session_previous_session_id')->nullable();
            $table->text('session_charge_status')->nullable();
            $table->string('session_parent_charge_status')->nullable();
            $table->text('session_overall_rating')->nullable();
            $table->text('session_engagement_rating')->nullable();
            $table->text('session_understanding_rating')->nullable();
            $table->binary('session_feedback')->nullable();
            $table->text('session_tutor_notes')->nullable();
            $table->integer('session_reminder')->default(0);
            $table->integer('session_before_session_reminder')->default(0);
            $table->integer('session_after_session_reminder')->default(0);
            $table->integer('session_after_session_reminder_18h')->default(0);
            $table->integer('session_after_session_reminder_30h')->default(0);
            $table->integer('session_after_session_reminder_42h')->default(0);
            $table->integer('session_parent_reminder')->default(0);
            $table->integer('tax_paid')->default(0);
            $table->integer('extra_expenses_paid')->default(0);
            $table->string('request_abn', 100)->nullable();
            $table->text('session_last_changed')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_sessions');
    }
};
