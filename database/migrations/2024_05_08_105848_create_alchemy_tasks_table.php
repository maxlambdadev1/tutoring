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
        Schema::create('alchemy_tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->integer('tutor_id');
            $table->string('task_subject');
            $table->string('task_name');
            $table->text('task_content');
            $table->integer('task_completed')->default(0);
            $table->integer('task_hidden')->default(0);
            $table->string('task_date', 50);
            $table->string('task_last_update', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_tasks');
    }
};
