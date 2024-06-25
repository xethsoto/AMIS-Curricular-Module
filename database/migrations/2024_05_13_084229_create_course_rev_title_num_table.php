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
        Schema::create('course_rev_title_num', function (Blueprint $table) {
            $table->foreignId('rev_id')->constrained('course_revisions')->cascadeOnDelete();
            $table->string('prev_code');
            $table->string('new_code')->nullable();
            $table->string('prev_title');
            $table->string('new_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_rev_title_num');
    }
};
