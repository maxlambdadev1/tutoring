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
        Schema::create('alchemy_jobs', function (Blueprint $table) {
            $table->id('job_id');
            $table->string('job_type', 100)->default('regular');
            $table->integer('replacement_id')->nullable();
            $table->integer('parent_id');
            $table->integer('child_id');
            $table->text('date');
            $table->text('time')->nullable();
            $table->string('start_date', 100)->nullable();
            $table->text('subject');
            $table->text('location');
            $table->string('prefered_gender', 10)->nullable();
            $table->longText('job_notes');
            $table->integer('job_status');
            $table->string('progress_status', 100)->nullable();
            $table->text('reason')->nullable();
            $table->integer('accepted_by')->nullable();
            $table->string('accepted_on', 100)->nullable();
            $table->integer('session_id')->nullable();
            $table->integer('hidden')->default(0);
            $table->text('source')->nullable();
            $table->text('converted_by')->nullable();
            $table->integer('3days_reminder')->default(0);
            $table->integer('6days_reminder')->default(0);
            $table->integer('9days_reminder')->default(0);
            $table->integer('welcome_call')->default(0);
            $table->string('callback_time', 100)->nullable();
            $table->string('create_time', 100)->default('0');
            $table->text('last_updated');
            $table->string('last_updated_for_waiting_list', 100)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->integer('session_type_id')->nullable();
            $table->string('contact_request', 50)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->tinyInteger('vaccinated')->default(0);
            $table->tinyInteger('experienced_tutor')->default(0);
            $table->tinyInteger('automation')->default(1);
            $table->tinyInteger('match_tutor')->default(2);
            $table->string('voucher_number', 100)->nullable();
            $table->string('main_result', 100)->nullable();
            $table->string('performance', 100)->nullable();
            $table->string('attitude', 100)->nullable();
            $table->string('mind', 100)->nullable();
            $table->string('personality', 100)->nullable();
            $table->string('favourite', 100)->nullable();
            $table->integer('thirdparty_org_id')->nullable();
            $table->text('special_request_content')->nullable();
            $table->text('special_request_response')->nullable();
            $table->text('tutor_suggested_session_date')->nullable();
            $table->integer('is_from_main')->default(0);
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_jobs');
    }
};
