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
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('application_id');
            $table->string('tutor_name')->nullable();
            $table->string('tutor_email')->nullable();
            $table->string('preferred_first_name')->nullable();
            $table->string('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('tutor_phone')->unique()->nullable();
            $table->string('photo')->nullable();

            $table->string('address')->nullable();
            $table->string('suburb')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();

            $table->string('abn')->unique()->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bsb')->nullable();
            $table->string('id_photo')->nullable();
            $table->string('id_photo_back')->nullable();
            $table->string('signature')->nullable();
            $table->string('tutor_stripe_user_id')->nullable();
            $table->boolean('tutor_stripe_status')->default(0);
            $table->boolean('onboarding')->default(0);

            $table->string('wwcc_application_number')->nullable();
            $table->string('wwcc_full_name')->nullable();
            $table->string('wwcc_number')->nullable();
            $table->string('wwcc_expiry')->nullable();

            $table->tinyInteger('cureent_status')->default(1);
            $table->string('degree')->nullable();
            $table->string('currentstudy')->nullable();
            $table->string('career')->nullable();
            $table->string('favourite')->nullable();
            $table->string('book_title')->nullable();
            $table->string('book_author')->nullable();
            $table->string('hobbies')->nullable();
            $table->text('question1')->nullable();
            $table->text('question2')->nullable();
            $table->text('question3')->nullable();
            $table->text('question4')->nullable();
            $table->text('greattutor')->nullable();
            $table->text('schoolattended')->nullable();
            $table->text('atar')->nullable();
            $table->text('achivement')->nullable();
            $table->text('expert_sub')->nullable();


            $table->text('availabilities')->nullable();
            $table->string('tutor_creat')->nullable();
            $table->boolean('tutor_status')->default(1);
            $table->text('notes')->nullable();
            $table->string('notes_last_updated')->nullable();
            $table->string('last_updated')->nullable();
            $table->boolean('accept_job_status')->default(1);
            $table->string('break_date')->nullable();
            $table->integer('break_count')->default(0);
            $table->string('online_automation_timestamp')->nullable();
            $table->integer('glassdoor')->default(0);
            $table->boolean('logged_status')->default(0);
            $table->boolean('online_acceptable_status')->default(0);
            $table->string('personal_details_last_update')->nullable();
            $table->string('subjects_last_update')->nullable();
            $table->string('payment_last_update')->nullable();
            $table->string('wwcc_last_update')->nullable();
            $table->timestamp('wwcc_last_verified')->nullable();            
            $table->string('online_url')->nullable();
            $table->string('referral_key')->unique();
            $table->string('child_safety_update')->nullable();
            $table->string('tag')->nullable();
            $table->boolean('experienced')->default(0);
            $table->boolean('vacinated')->default(0);
            $table->boolean('seeking_students')->default(0);
            $table->boolean('non_metro')->default(0);
            $table->string('seeking_students_timestamp')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
