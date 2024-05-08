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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('role')->default(2)->comment('tutor as default');    // tutor as default
            $table->tinyInteger('login_status')->default(0)->comment('0:logged out, 1:logged in');    // 0:logged out, 1:logged in
            $table->tinyInteger('active')->default(1)->comment('0:blocked, 1:active, 2:pending');    // 0:blocked, 1:active, 2:pending
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
