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
        Schema::create('citizens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('fiscal_code', 16);
            $table->softDeletes();
        });

        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('head_citizen_id');
            $table->timestamps();

            $table->foreign('head_citizen_id')->references('id')->on('citizens');
        });

        Schema::create('citizen_family', function (Blueprint $table) {
            $table->foreignId('citizen_id')->constrained();
            $table->foreignId('family_id')->constrained();
            $table->enum('role', ['parent', 'tutor', 'child']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_tables');
    }
};
