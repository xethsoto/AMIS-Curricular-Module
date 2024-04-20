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
        Schema::create('prop_courses_to_new_curriculum', function (Blueprint $table) {
            $table->foreignId('new_curriculum_id')->constrained('deg_prog_prop_curriculum')->cascadeOnDelete();
            $table->integer('year_to_be_taken');
            $table->integer('sem_to_be_taken');
            $table->string('course_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prop_courses_to_new_curriculum');
    }
};
