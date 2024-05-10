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
        Schema::create('alchemy_children', function (Blueprint $table) {
            $table->id('children_id');
            $table->integer('parent_id');
            $table->string('child_name');
            $table->string('child_first_name')->nullable();
            $table->string('child_last_name')->nullable();
            $table->string('year', 10);
            $table->string('child_birthday', 11)->nullable();
            $table->string('child_school')->nullable();
            $table->integer('child_status')->default(1);
            $table->integer('child_attitude')->nullable();
            $table->integer('child_confidence')->nullable();
            $table->integer('child_ability')->nullable();
            $table->string('graduation_year', 50)->nullable();
            $table->tinyInteger('google_ads')->default(0);
            $table->tinyInteger('follow_up')->default(0)->nullable();
            $table->text('no_follow_up_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_children');
    }
};
