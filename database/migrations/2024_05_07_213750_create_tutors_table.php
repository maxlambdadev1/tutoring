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
            $table->integer('state_id');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('preferred_first_name')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('tutor_phone')->unique()->nullable();
            $table->string('photo')->nullable();

            $table->string('address')->nullable();
            $table->string('suburb')->nullable();
            $table->string('postcode')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();

            $table->string('abn')->unique()->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bsb')->nullable();
            $table->string('id_photo')->nullable();
            $table->string('signature')->nullable();
            $table->string('stripe_id')->nullable();

            $table->string('wwcc_application_number')->nullable();
            $table->string('wwcc_full_name')->nullable();
            $table->string('wwcc_number')->nullable();
            $table->date('wwcc_expiry')->nullable();

            $table->json('availabilities')->nullable();
            $table->string('online_url')->nullable();
            $table->string('referral_key')->unique();
            $table->boolean('vacinated')->default(0);
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
