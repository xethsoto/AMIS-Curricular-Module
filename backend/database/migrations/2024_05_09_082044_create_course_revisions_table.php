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
        Schema::create('course_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('title')->nullable();
            $table->text('desc')->nullable();
            $table->integer('credit')->nullable();
            $table->integer('num_of_hours')->nullable();
            $table->text('goal')->nullable();
            $table->text('outline')->nullable();
            $table->foreignId('prop_id')->constrained('proposals')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_revisions');
    }
};
