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
        Schema::create('postcode_db', function (Blueprint $table) {
            $table->id();       
            $table->string('suburb', 45);
            $table->string('state', 4);
            $table->string('dc', 45);
            $table->string('type', 45);
            $table->double('lat')->default(0);
            $table->double('lon')->default(0);            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postcode_db');
    }
};
