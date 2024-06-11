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
        Schema::create('alchemy_tutor_application', function (Blueprint $table) {
            $table->id();
            $table->string('tutor_first_name');
            $table->string('tutor_last_name');
            $table->string('tutor_phone');
            $table->string('tutor_email');
            $table->string('tutor_state');
            $table->string('tutor_suburb');
            $table->string('postcode', 100)->nullable();
            $table->string('tutor_referral')->nullable();
            $table->string('tutor_graduation_year')->nullable();
            $table->string('tutor_highschool_aus')->nullable();
            $table->string('tutor_atar', 100)->nullable();
            $table->string('tutor_school')->nullable();
            $table->text('tutor_achievements')->nullable();
            $table->text('tutor_current_situation')->nullable();
            $table->text('tutor_current_university')->nullable();
            $table->text('tutor_graduated_university')->nullable();
            $table->text('tutor_current_degree')->nullable();
            $table->text('tutor_graduated_degree')->nullable();
            $table->text('tutor_future_university')->nullable();
            $table->text('tutor_future_degree')->nullable();
            $table->text('tutor_introduction')->nullable();
            $table->text('tutor_dinner')->nullable();
            $table->text('tutor_advice')->nullable();
            $table->text('tutor_subjects');
            $table->text('tutor_tutored_before')->nullable();
            $table->text('tutor_good_tutor')->nullable();
            $table->string('tutor_application_source')->nullable();
            $table->text('tutor_application_experience')->nullable();
            $table->string('tutor_application_car', 100)->nullable();
            $table->string('tutor_application_car_easy', 100)->nullable();
            $table->string('tutor_fname1', 100)->nullable();
            $table->string('tutor_lname1', 100)->nullable();
            $table->string('tutor_fname2', 100)->nullable();
            $table->string('tutor_lname2', 100)->nullable();
            $table->string('tutor_email1', 100)->nullable();
            $table->string('tutor_relation1', 100)->nullable();
            $table->string('tutor_email2', 100)->nullable();
            $table->string('interview_date', 100)->nullable();
            $table->string('tag', 200)->nullable();
            $table->string('date_submitted', 100);
            $table->string('date_last_update', 100)->nullable();
            $table->tinyInteger('reference_respond_status')->default(0);
            $table->string('reference_update', 100)->nullable();
            $table->tinyInteger('reference_reminder_48h')->default(0);
            $table->integer('reference_reminder_96h')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_tutor_application');
    }
};
