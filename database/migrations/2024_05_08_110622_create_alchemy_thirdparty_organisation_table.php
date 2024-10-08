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
        Schema::create('alchemy_thirdparty_organisation', function (Blueprint $table) {
            $table->id();
            $table->string('organisation_name');
            $table->string('primary_contact_first_name');
            $table->string('primary_contact_last_name');
            $table->string('primary_contact_role')->nullable();
            $table->string('primary_contact_phone')->nullable();
            $table->string('primary_contact_email');
            $table->string('email_for_billing')->nullable();
            $table->string('email_for_confirmations')->nullable();
            $table->mediumText('comment');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_thirdparty_organisation');
    }
};
