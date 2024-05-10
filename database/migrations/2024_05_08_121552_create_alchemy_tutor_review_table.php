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
        Schema::create('alchemy_tutor_review', function (Blueprint $table) {
            $table->id();
            $table->integer('tutor_id');
            $table->integer('parent_id');
            $table->integer('child_id');
            $table->integer('progress_report_id');
            $table->integer('rating')->nullable();
            $table->text('rating_comment');
            $table->tinyInteger('approved')->default(0);
            $table->tinyInteger('emailed')->default(0);
            $table->tinyInteger('hidden')->default(0);
            $table->integer('reject')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->string('date_lastupdated', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_tutor_review');
    }
};
