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
        Schema::create('course_institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('desc');
            $table->integer('credit');
            $table->string('num_of_hours');
            $table->text('goal');
            $table->text('outline');
            $table->string('univ_origin')->nullable();
            $table->foreignId('prop_id')->constrained('proposals')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_institutions');
    }
};
