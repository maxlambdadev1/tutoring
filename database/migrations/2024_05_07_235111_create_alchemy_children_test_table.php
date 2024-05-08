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
        Schema::create('alchemy_children_test', function (Blueprint $table) {
            $table->id('children_id');
            $table->integer('parent_id')->comment('parent id from alchemy_parent table');
            $table->string('child_name');
            $table->string('child_first_name', 100)->nullable();
            $table->string('child_last_name', 100)->nullable();
            $table->string('child_year', 10);
            $table->string('child_school')->nullable();
            $table->integer('child_status')->default(0);
            $table->integer('child_attitude')->nullable();
            $table->integer('child_confidence')->nullable();
            $table->integer('child_ability')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alchemy_children_test');
    }
};
