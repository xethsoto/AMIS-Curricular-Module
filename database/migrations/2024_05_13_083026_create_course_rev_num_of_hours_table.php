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
        Schema::create('course_rev_num_of_hours', function (Blueprint $table) {
            $table->foreignId('rev_id')->constrained('course_revisions')->cascadeOnDelete();
            $table->string('prev_num_of_hours');
            $table->string('new_num_of_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_rev_num_of_hours');
    }
};
