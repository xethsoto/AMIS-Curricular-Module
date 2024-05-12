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
        Schema::create('course_revision_sem_offered', function (Blueprint $table) {
            $table->foreignId('course_rev_id')->constrained('course_revisions')->cascadeOnDelete();
            $table->string('new_sem_offered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_revision_sem_offered');
    }
};
