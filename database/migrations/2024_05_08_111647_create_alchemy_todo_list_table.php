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
        Schema::create('alchemy_todo_list', function (Blueprint $table) {
            $table->id();
            $table->integer('task_status')->default(0);
            $table->integer('assigned_to');
            $table->text('description');
            $table->integer('created_by');
            $table->string('followup', 100)->nullable();
            $table->string('due_date',100)->nullable();
            $table->integer('email_sent')->default(0);
            $table->string('created_at', 100);
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_todo_list');
    }
};
